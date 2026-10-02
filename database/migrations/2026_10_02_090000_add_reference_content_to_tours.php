<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Columns needed to mirror the live reference site (ziaratravels.co.ke).
 *
 * pricing_options - the reference renders a multi-option pricing box on every
 *   tour detail page (for example "KES 64,000 per person sharing" beside two or
 *   three hotel tiers). `tours.price` can only hold a single amount, so the full
 *   list of labelled options lives here and the cheapest tier stays in `price`.
 *
 * card_excerpt - the short teaser the reference prints under some cards.
 *
 * gallery - the detail-page gallery images. Kept separate from `images` because
 *   a tour may have no listing card image at all (it is absent from the
 *   homepage carousel), which makes the roles ambiguous within a single list.
 */
class AddReferenceContentToTours extends Migration
{
    /** @var list<string> */
    private array $columns = ['pricing_options', 'card_excerpt', 'gallery'];

    public function up(): void
    {
        Schema::table('tours', function (Blueprint $table) {
            if (! Schema::hasColumn('tours', 'pricing_options')) {
                $table->json('pricing_options')->nullable()->after('price');
            }
            if (! Schema::hasColumn('tours', 'card_excerpt')) {
                $table->text('card_excerpt')->nullable()->after('pricing_options');
            }
            if (! Schema::hasColumn('tours', 'gallery')) {
                $table->json('gallery')->nullable()->after('card_excerpt');
            }
        });
    }

    public function down(): void
    {
        $existing = array_filter(
            $this->columns,
            fn (string $column) => Schema::hasColumn('tours', $column)
        );

        if ($existing === []) {
            return;
        }

        Schema::table('tours', function (Blueprint $table) use ($existing) {
            $table->dropColumn($existing);
        });
    }
}