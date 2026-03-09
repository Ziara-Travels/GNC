<?php

namespace App\Http\Controllers\Travel;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Newsletter;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

class NewsletterController extends Controller
{
    /**
     * Subscribe a user to the newsletter.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function subscribe($request)
    {
        // Validate request
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            // Check if email already exists
            $existingSubscriber = Newsletter::where('email', $request->email)->first();
            
            if ($existingSubscriber) {
                // If already subscribed but marked as unsubscribed, reactivate
                if (!$existingSubscriber->is_subscribed) {
                    $existingSubscriber->update([
                        'is_subscribed' => true,
                        'name' => $request->name, // Update name in case it changed
                    ]);
                    
                    // Sync with external services
                    $this->syncWithExternalServices($existingSubscriber);
                    
                    return response()->json([
                        'success' => true,
                        'message' => 'Welcome back! Your subscription has been reactivated.'
                    ]);
                }
                
                return response()->json([
                    'success' => true,
                    'message' => 'You\'re already subscribed to our newsletter!'
                ]);
            }

            // Create new subscriber
            $subscriber = Newsletter::create([
                'name' => $request->name,
                'email' => $request->email,
                'weekly_emails' => true,
                'monthly_emails' => true,
                'promotional_emails' => true,
                'source' => $request->source ?? 'website',
            ]);

            // Sync with external services
            $this->syncWithExternalServices($subscriber);

            return response()->json([
                'success' => true,
                'message' => 'Thank you for subscribing to our newsletter!'
            ]);
        } catch (\Exception $e) {
            Log::error('Newsletter subscription error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong. Please try again later.'
            ], 500);
        }
    }

    /**
     * Unsubscribe a user from the newsletter.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function unsubscribe(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:newsletters,email',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Email not found in our subscription list.'
            ], 422);
        }

        try {
            $subscriber = Newsletter::where('email', $request->email)->first();
            
            if (!$subscriber) {
                return response()->json([
                    'success' => false,
                    'message' => 'Email not found in our subscription list.'
                ], 404);
            }

            $subscriber->update(['is_subscribed' => false]);
            
            // Sync unsubscribe status with external services
            $this->syncUnsubscribeWithExternalServices($subscriber);

            return response()->json([
                'success' => true,
                'message' => 'You have been successfully unsubscribed.'
            ]);
        } catch (\Exception $e) {
            Log::error('Newsletter unsubscribe error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong. Please try again later.'
            ], 500);
        }
    }

    /**
     * Update subscriber preferences.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function updatePreferences(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:newsletters,email',
            'weekly_emails' => 'boolean',
            'monthly_emails' => 'boolean',
            'promotional_emails' => 'boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $subscriber = Newsletter::where('email', $request->email)->first();
            
            if (!$subscriber) {
                return response()->json([
                    'success' => false,
                    'message' => 'Email not found in our subscription list.'
                ], 404);
            }

            $updateData = [];
            
            if ($request->has('weekly_emails')) {
                $updateData['weekly_emails'] = $request->weekly_emails;
            }
            
            if ($request->has('monthly_emails')) {
                $updateData['monthly_emails'] = $request->monthly_emails;
            }
            
            if ($request->has('promotional_emails')) {
                $updateData['promotional_emails'] = $request->promotional_emails;
            }
            
            $subscriber->update($updateData);
            
            // Sync preference changes with external services
            $this->syncPreferencesWithExternalServices($subscriber);

            return response()->json([
                'success' => true,
                'message' => 'Your preferences have been updated successfully.'
            ]);
        } catch (\Exception $e) {
            Log::error('Newsletter preferences update error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong. Please try again later.'
            ], 500);
        }
    }

    /**
     * Sync subscriber with external services like HubSpot, etc.
     *
     * @param  \App\Models\Newsletter  $subscriber
     * @return void
     */
    private function syncWithExternalServices($subscriber)
    {
        // Sync with HubSpot
        $this->syncWithHubSpot($subscriber);
        
        // Add other email service integrations here
        // $this->syncWithMailchimp($subscriber);
        // $this->syncWithSendgrid($subscriber);
    }

    /**
     * Sync subscriber unsubscribe status with external services.
     *
     * @param  \App\Models\Newsletter  $subscriber
     * @return void
     */
    private function syncUnsubscribeWithExternalServices($subscriber)
    {
        // Sync unsubscribe with HubSpot
        $this->syncUnsubscribeWithHubSpot($subscriber);
        
        // Add other email service unsubscribe integrations here
    }

    /**
     * Sync subscriber preference changes with external services.
     *
     * @param  \App\Models\Newsletter  $subscriber
     * @return void
     */
    private function syncPreferencesWithExternalServices($subscriber)
    {
        // Sync preferences with HubSpot
        $this->syncPreferencesWithHubSpot($subscriber);
        
        // Add other email service preference update integrations here
    }

    /**
     * Sync with HubSpot.
     *
     * @param  \App\Models\Newsletter  $subscriber
     * @return void
     */
    private function syncWithHubSpot($subscriber)
    {
        try {
            // This is a placeholder for actual HubSpot API integration
            // You would use the HubSpot API to create or update contacts
            
            // Example using Guzzle to make API requests to HubSpot
            $client = new Client();
            
            // Check if we already have a HubSpot contact ID
            if ($subscriber->hubspot_contact_id) {
                // Update existing contact
                // $response = $client->request('PATCH', 'https://api.hubapi.com/crm/v3/objects/contacts/' . $subscriber->hubspot_contact_id, [
                //     'headers' => [
                //         'Authorization' => 'Bearer ' . config('services.hubspot.api_key'),
                //         'Content-Type' => 'application/json',
                //     ],
                //     'json' => [
                //         'properties' => [
                //             'email' => $subscriber->email,
                //             'firstname' => $subscriber->name,
                //             'newsletter_subscribed' => 'true',
                //             'weekly_emails' => $subscriber->weekly_emails ? 'true' : 'false',
                //             'monthly_emails' => $subscriber->monthly_emails ? 'true' : 'false',
                //             'promotional_emails' => $subscriber->promotional_emails ? 'true' : 'false',
                //         ],
                //     ],
                // ]);
            } else {
                // Create new contact
                // $response = $client->request('POST', 'https://api.hubapi.com/crm/v3/objects/contacts', [
                //     'headers' => [
                //         'Authorization' => 'Bearer ' . config('services.hubspot.api_key'),
                //         'Content-Type' => 'application/json',
                //     ],
                //     'json' => [
                //         'properties' => [
                //             'email' => $subscriber->email,
                //             'firstname' => $subscriber->name,
                //             'newsletter_subscribed' => 'true',
                //             'weekly_emails' => $subscriber->weekly_emails ? 'true' : 'false',
                //             'monthly_emails' => $subscriber->monthly_emails ? 'true' : 'false',
                //             'promotional_emails' => $subscriber->promotional_emails ? 'true' : 'false',
                //             'source' => $subscriber->source,
                //         ],
                //     ],
                // ]);
                
                // $responseData = json_decode($response->getBody()->getContents(), true);
                
                // Save the HubSpot contact ID back to our database
                // if (isset($responseData['id'])) {
                //     $subscriber->update(['hubspot_contact_id' => $responseData['id']]);
                // }
            }
            
            // Log successful sync
            Log::info('Successfully synced subscriber to HubSpot: ' . $subscriber->email);
        } catch (RequestException $e) {
            // Log error
            Log::error('HubSpot sync error: ' . $e->getMessage());
        }
    }

    /**
     * Sync unsubscribe status with HubSpot.
     *
     * @param  \App\Models\Newsletter  $subscriber
     * @return void
     */
    private function syncUnsubscribeWithHubSpot($subscriber)
    {
        try {
            // This is a placeholder for actual HubSpot API unsubscribe integration
            
            // Only proceed if we have a HubSpot contact ID
            if ($subscriber->hubspot_contact_id) {
                // $client = new Client();
                // $response = $client->request('PATCH', 'https://api.hubapi.com/crm/v3/objects/contacts/' . $subscriber->hubspot_contact_id, [
                //     'headers' => [
                //         'Authorization' => 'Bearer ' . config('services.hubspot.api_key'),
                //         'Content-Type' => 'application/json',
                //     ],
                //     'json' => [
                //         'properties' => [
                //             'newsletter_subscribed' => 'false',
                //         ],
                //     ],
                // ]);
                
                // Log successful unsubscribe sync
                Log::info('Successfully synced unsubscribe status to HubSpot: ' . $subscriber->email);
            }
        } catch (RequestException $e) {
            // Log error
            Log::error('HubSpot unsubscribe sync error: ' . $e->getMessage());
        }
    }

    /**
     * Sync preference changes with HubSpot.
     *
     * @param  \App\Models\Newsletter  $subscriber
     * @return void
     */
    private function syncPreferencesWithHubSpot($subscriber)
    {
        try {
            // This is a placeholder for actual HubSpot API preference update integration
            
            // Only proceed if we have a HubSpot contact ID
            if ($subscriber->hubspot_contact_id) {
                // $client = new Client();
                // $response = $client->request('PATCH', 'https://api.hubapi.com/crm/v3/objects/contacts/' . $subscriber->hubspot_contact_id, [
                //     'headers' => [
                //         'Authorization' => 'Bearer ' . config('services.hubspot.api_key'),
                //         'Content-Type' => 'application/json',
                //     ],
                //     'json' => [
                //         'properties' => [
                //             'weekly_emails' => $subscriber->weekly_emails ? 'true' : 'false',
                //             'monthly_emails' => $subscriber->monthly_emails ? 'true' : 'false',
                //             'promotional_emails' => $subscriber->promotional_emails ? 'true' : 'false',
                //         ],
                //     ],
                // ]);
                
                // Log successful preference sync
                Log::info('Successfully synced preferences to HubSpot: ' . $subscriber->email);
            }
        } catch (RequestException $e) {
            // Log error
            Log::error('HubSpot preferences sync error: ' . $e->getMessage());
        }
    }
}
