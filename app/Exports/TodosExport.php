<?php

namespace App\Exports;

use App\DurationHumanizer;
use App\Models\Todo;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class TodosExport implements FromCollection,WithHeadings, WithEvents, WithMapping, WithColumnFormatting
{
    use DurationHumanizer;

    private int $rowNum = 0;
    private int $timeTracker = 0;

    protected $todos;

    public function __construct(Collection $todos) {
        $this->todos = $todos;
    }

    /**
     * @return \Illuminate\Support\Collection
     */

    public function collection()
    {
        return $this->todos;
    }

    /**
    * @return array
    */
    public function headings(): array
    {
        //column headers
        return [
            'No', //col A
            'Title', //col B
            'Assignee', //col C
            'Due Date', //col D
            'Time Tracked', //col E
            'Status', //col F
            'Priority' //col G
        ];
    }

    /**
    * @return array
    */
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event){
                //add custom summary row
                $lastRow = $event->sheet->getDelegate()->getHighestRow();
                $totalTodos = $lastRow + 1;
                $totalTimeTracked = $totalTodos + 1;
                //get total todos
                $allTodos = $this->todos->count();
                //placing total todos data in rows
                $event->sheet->getDelegate()->setCellValue("B{$totalTodos}", 'Total Todos:');
                $event->sheet->getDelegate()->setCellValue("C{$totalTodos}", $allTodos);

                //get time_tracked accross all todos
                $sumTime = $this->timeTracker;
                $formattedSumTime = $this->convert($sumTime);
                //placing time tracked data in rows
                $event->sheet->getDelegate()->setCellValue("B{$totalTimeTracked}", 'Total Time Tracked:');
                $event->sheet->getDelegate()->setCellValue("C{$totalTimeTracked}", $formattedSumTime);
            }
        ];
    }

    /**
     * @return array
     */

     public function map($todo) : array {
        $this->rowNum++;
        $this->timeTracker = $this->timeTracker + $todo->time_tracked;
        return [
            $this->rowNum,
            $todo->title,
            $todo->assignee,
            $todo->due_date,
            $todo->time_tracked / 86400,
            $todo->status,
            $todo->priority,
        ];
     }

    /**
     * @return array
     */

    public function columnFormats(): array
    {
        return [
            'D' => NumberFormat::FORMAT_DATE_DMYMINUS, // Applies 'd/m/y' format
            'E' => '[h]:mm:ss', // Applies 'hh:mm:ss' format
        ];
    }

}
