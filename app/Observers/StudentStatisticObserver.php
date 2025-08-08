<?php

namespace App\Observers;

use App\Models\DailyHostelSummary;
use App\Models\StudentStatistic;

class StudentStatisticObserver
{
    /**
     * Handle the StudentStatistic "created" event.
     * This is triggered IMMEDIATELY after a new record is saved to the database.
     */
    public function created(StudentStatistic $studentStatistic): void
    {
        // Safety check: ensure the related hostel exists.
        if (!$studentStatistic->hostel) {
            return;
        }

        // Prepare the data we want to save.
        // The column we update depends on the shift of the new record.
        $summaryData = [
            'capacity' => $studentStatistic->hostel->number_of_students, // Snapshot capacity
        ];

        if (strtolower($studentStatistic->shift) === 'day') {
            $summaryData['students_present_day'] = $studentStatistic->students_present;
        } else {
            $summaryData['students_present_night'] = $studentStatistic->students_present;
        }

        // The Magic: `updateOrCreate`
        // 1. It looks for a row in `daily_hostel_summaries` matching the first array.
        // 2. If it finds one, it UPDATES that row with the data from the second array.
        // 3. If it doesn't find one, it CREATES a new row by merging both arrays.
        DailyHostelSummary::updateOrCreate(
            [
                'hostel_id' => $studentStatistic->hostel_id,
                'date'      => $studentStatistic->record_date,
            ],
            $summaryData
        );
    }
}