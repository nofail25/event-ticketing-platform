<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('ticket_categories')
            ->leftJoin('ticket_details', 'ticket_categories.id', '=', 'ticket_details.ticket_category_id')
            ->select('ticket_categories.id', DB::raw('COUNT(ticket_details.id) as sold_count'))
            ->groupBy('ticket_categories.id')
            ->orderBy('ticket_categories.id')
            ->each(function (object $category): void {
                if ($category->sold_count > 0) {
                    DB::table('ticket_categories')
                        ->where('id', $category->id)
                        ->increment('quota', $category->sold_count);
                }
            });
    }

    public function down(): void
    {
        DB::table('ticket_categories')
            ->leftJoin('ticket_details', 'ticket_categories.id', '=', 'ticket_details.ticket_category_id')
            ->select('ticket_categories.id', DB::raw('COUNT(ticket_details.id) as sold_count'))
            ->groupBy('ticket_categories.id')
            ->orderBy('ticket_categories.id')
            ->each(function (object $category): void {
                if ($category->sold_count > 0) {
                    DB::table('ticket_categories')
                        ->where('id', $category->id)
                        ->decrement('quota', $category->sold_count);
                }
            });
    }
};
