<?php

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Writer\Csv;

class PhpExcel
{
	public $title;
	public $subject;
	public $creator;
	public $description;
	public $lastModifiedBy;
	public $setProperties;

	public function export($data)
	{
		if (empty($data['columns']) || empty($data['results']) || empty($data['extension']) || empty($data['redirectUrl']))
		{
			if (isset($_SERVER['HTTP_REFERER']))
			{
				header('Location: '. $_SERVER['HTTP_REFERER']);
			}
			else
			{
				show_404();
			}
		}

	

		$spreadsheet = new Spreadsheet();
		$sheet = $spreadsheet->getActiveSheet();

		if ($this->setProperties)
		{
			$spreadsheet->getProperties()->setCreator($this->creator)
				->setLastModifiedBy($this->lastModifiedBy)
				->setTitle($this->title)
				->setSubject($this->subject)
				->setDescription($this->description);
		}

		$styleArray = [
			'font' => [
				'bold' => true,
			],
			'alignment' => [
				'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
				'vertical'   => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
			],
			'borders' => [
				'bottom' => [
					'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK,
					'color' => ['rgb' => '333333'],
				],
			],
			'fill' => [
				'type'       => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_GRADIENT_LINEAR,
				'rotation'   => 90,
				'startcolor' => ['rgb' => '0d0d0d'],
				'endColor'   => ['rgb' => 'f2f2f2'],
			],
		];

		$spreadsheet->getActiveSheet()->getStyle('A1:G1')->applyFromArray($styleArray);
		
		// auto fit column to content
		foreach(range('A', 'G') as $columnID) {
			$spreadsheet->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
		}

		$alphabates = range('A', 'Z');
		$x = 1;

		foreach($data['columns'] as $columnIndex => $column)
		{
			// set the names of header cells
			$sheet->setCellValue(sprintf('%s%s', $alphabates[$columnIndex], $x), $column['title']);
		}

		++$x;

		foreach($data['results'] as $result)
		{
			foreach($data['columns'] as $index => $columnData)
			{
				$sheet->setCellValue(sprintf('%s%s', $alphabates[$index], $x), $result[$columnData['name']]);
			}
			$x++;
		}

		$filename = sprintf('products-%s', time());
		$extension = strtolower($data['extension']);

		if ($extension == 'csv')
		{          
			$writer = new Csv($spreadsheet);
			$filename = $filename.'.csv';
		} 
		else if($extension == 'excel') 
		{
			$writer = new Xlsx($spreadsheet);
			$filename = $filename.'.xlsx';
		}
		
		$writer->save($filename);

		header('Content-type: application/vnd.ms-excel');
		header('Content-type: application/csv');
		header('Content-Disposition: attachment; filename="'.$filename.'"');

		// Write file to the browser
		$writer->save('php://output');

		// redirect($data['redirectUrl']);
 	}
}
