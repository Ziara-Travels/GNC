<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use App\Mail\SupportMessage;
use App\Mail\HelpMessage;
use App\Mail\HelpConfirmation;
use App\Mail\SupportConfirmation;

class SupportController extends Controller
{
    /**
     * Display the contact us page
     */
    public function index()
    {
        return view('pages.contactus');
    }

    /**
     * Handle contact form submission
     */
    public function submit(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'subject' => 'required|string|max:255',
            'message' => 'required|string|max:5000',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $supportData = [
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'subject' => $request->subject,
            'message' => $request->message,
            'submitted_at' => now(),
            'type' => 'contact_request'
        ];

        try {
            // Send email to admin
            Mail::to(config('mail.admin_email', 'admin@ziaratravel.com'))
                ->send(new SupportMessage($supportData));

            // Send confirmation email to user
            Mail::to($request->email)
                ->send(new SupportConfirmation($supportData));

            return redirect()->back()->with('success', 'Your message has been sent successfully! We\'ll get back to you soon.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Sorry, there was an error sending your message. Please try again.');
        }
    }

    /**
     * Display the help page
     */
    public function help()
    {
        return view('pages.help');
    }

    /**
     * Handle help form submission
     */
    public function contact(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'subject' => 'required|string|max:255',
            'message' => 'required|string|max:5000',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $helpData = [
            'name' => $request->name,
            'email' => $request->email,
            'subject' => $request->subject,
            'message' => $request->message,
            'submitted_at' => now(),
            'type' => 'help_request'
        ];

        try {
            // Send email to admin
            Mail::to(config('mail.admin_email', 'help@ziaratravel.com'))
                ->send(new HelpMessage($helpData));

            // Send confirmation email to user
            Mail::to($request->email)
                ->send(new HelpConfirmation($helpData));

            return redirect()->back()->with('success', 'Your message has been sent successfully! We\'ll get back to you soon.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Sorry, there was an error sending your message. Please try again.');
        }
    }

    /**
     * Display services page
     */
    public function services()
    {
        return view('pages.services');
    }

    /**
     * Display trip planning page
     */
    public function tripPlanning()
    {
        return view('pages.trip-planning');
    }

    /**
     * Handle trip planning form submission
     */
    public function submitTripPlanning(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'destination' => 'required|string|max:255',
            'travel_dates' => 'required|string|max:255',
            'budget' => 'nullable|string|max:100',
            'travelers' => 'required|integer|min:1',
            'preferences' => 'nullable|string|max:2000',
            'special_requirements' => 'nullable|string|max:2000',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $tripData = [
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'destination' => $request->destination,
            'travel_dates' => $request->travel_dates,
            'budget' => $request->budget,
            'travelers' => $request->travelers,
            'preferences' => $request->preferences,
            'special_requirements' => $request->special_requirements,
            'submitted_at' => now(),
            'type' => 'trip_planning'
        ];

        try {
            // Send email to admin
            Mail::to(config('mail.admin_email', 'trips@ziaratravel.com'))
                ->send(new SupportMessage($tripData));

            // Send confirmation email to user
            Mail::to($request->email)
                ->send(new SupportConfirmation($tripData));

            return redirect()->back()->with('success', 'Your trip planning request has been submitted! Our travel experts will contact you soon.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Sorry, there was an error submitting your request. Please try again.');
        }
    }

    /**
     * Display insurance page
     */
    public function insurance()
    {
        return view('pages.insurance');
    }

    /**
     * Handle insurance inquiry form submission
     */
    public function submitInsurance(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'destination' => 'required|string|max:255',
            'travel_dates' => 'required|string|max:255',
            'coverage_type' => 'required|string|max:100',
            'travelers' => 'required|integer|min:1',
            'medical_conditions' => 'nullable|string|max:2000',
            'additional_info' => 'nullable|string|max:2000',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $insuranceData = [
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'destination' => $request->destination,
            'travel_dates' => $request->travel_dates,
            'coverage_type' => $request->coverage_type,
            'travelers' => $request->travelers,
            'medical_conditions' => $request->medical_conditions,
            'additional_info' => $request->additional_info,
            'submitted_at' => now(),
            'type' => 'insurance_inquiry'
        ];

        try {
            // Send email to admin
            Mail::to(config('mail.admin_email', 'insurance@ziaratravel.com'))
                ->send(new SupportMessage($insuranceData));

            // Send confirmation email to user
            Mail::to($request->email)
                ->send(new SupportConfirmation($insuranceData));

            return redirect()->back()->with('success', 'Your insurance inquiry has been submitted! We\'ll provide you with quotes and options soon.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Sorry, there was an error submitting your inquiry. Please try again.');
        }
    }

    /**
     * Display about us page
     */
    public function aboutUs()
    {
        return view('pages.aboutus');
    }

    /**
     * Display privacy policy page
     */
    public function privacyPolicy()
    {
        return view('pages.privacy-policy');
    }

    /**
     * Display terms of service page
     */
    public function termsOfService()
    {
        return view('pages.terms-of-service');
    }

      /**
     * Display terms of support page
     */
    public function support()
    {
        return view('pages.support');
    }

    public function submitFeedback(Request $request)
        {
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'email' => 'required|email|max:255',
                'service' => 'required|string|max:100',
                'rating' => 'required|integer|min:1|max:5',
                'feedback' => 'required|string|max:2000',
            ]);

            if ($validator->fails()) {
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput();
            }

            $feedbackData = [
                'name' => $request->name,
                'email' => $request->email,
                'service' => $request->service,
                'rating' => $request->rating,
                'feedback' => $request->feedback,
                'submitted_at' => now(),
                'type' => 'feedback'
            ];

            try {
                // Store feedback in database or send email
                // You can implement this according to your needs

                return redirect()->back()->with('success', 'Thank you for your feedback! We appreciate your input.');
            } catch (\Exception $e) {
                return redirect()->back()->with('error', 'Sorry, there was an error submitting your feedback. Please try again.');
            }
        }

// ==================== MEGA MENU ROUTES ====================

    /**
     * Display what we do page
     */
    public function whatWeDo()
    {
        return view('pages.what-we-do');
    }

    /**
     * Display FAQs page
     */
    public function faqs()
    {
        return view('pages.faq');
    }

    /**
     * Display customer stories page
     */
    public function customerStories()
    {
        return view('pages.customer-stories');
    }

    /**
     * Display blog page
     */
    public function blog()
    {
        return view('pages.blog');
    }

    /**
     * Display news page
     */
    public function news()
    {
        return view('pages.news');
    }

    /**
     * Display events page
     */
    public function events()
    {
        return view('pages.events');
    }

    /**
     * Display careers page
     */
    public function careers()
    {
        return view('pages.careers');
    }

    /**
     * Display why choose us page
     */
    public function whyChooseUs()
    {
        return view('pages.why-choose-us');
    }

    /**
     * Display achievements page
     */
    public function achievements()
    {
        return view('pages.achievements');
    }

    /**
     * Display referral program page
     */
    public function referralProgram()
    {
        return view('pages.referral-program');
    }

    /**
     * Display loyalty points page
     */
    public function loyaltyPoints()
    {
        return view('pages.loyalty-points');
    }

    /**
     * Display special offers page
     */
    public function specialOffers()
    {
        return view('pages.special-offers');
    }

    /**
     * Display VIP membership page
     */
    public function vipMembership()
    {
        return view('pages.vip-membership');
    }


    /**
     * Display travel insurance page
     */
    public function travelInsurance()
    {
        return view('pages.travel-insurance');
    }

    /**
     * Display help center page
     */
    public function helpCenter()
    {
        return view('pages.help-center');
    }

    /**
     * Display live chat page
     */
    public function liveChat()
    {
        return view('pages.live-chat');
    }


}