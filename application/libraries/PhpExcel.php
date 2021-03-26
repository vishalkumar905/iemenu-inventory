<?php

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Writer\Csv;
use PhpOffice\PhpSpreadsheet\Reader\Csv as csvReader;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx as xlsxReader;

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

		$alphabates = $this->makeAlphabateRangeForExcelSheet();;
		$columnCount = count($data['columns']);
		$lastActiveCellName = '';

		if ($columnCount < count($alphabates))
		{
			$lastActiveCellName = $alphabates[$columnCount - 1];
			$spreadsheet->getActiveSheet()->getStyle('A1:'.$lastActiveCellName.'1')->applyFromArray($styleArray);
			$spreadsheet->getActiveSheet()->getStyle('A1:'.$lastActiveCellName.'1')->getAlignment()->setHorizontal('center');
		}

		$x = 1;

		foreach($data['columns'] as $columnIndex => $column)
		{
			// auto fit column to content
			$spreadsheet->getActiveSheet()->getColumnDimension($alphabates[$columnIndex])->setAutoSize(true);
			
			// set the names of header cells
			$sheet->setCellValue(sprintf('%s%s', $alphabates[$columnIndex], $x), $column['title']);
		}

		if ($lastActiveCellName)
		{
			$sheet->getStyle('A:'.$lastActiveCellName)->getAlignment()->setHorizontal('center');
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

		$filename = sprintf('%s-%s', $data['fileName'], time());
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
		
		// $writer->save($filename);

		header('Content-type: application/vnd.ms-excel');
		header('Content-type: application/csv');
		header('Content-Disposition: attachment; filename="'.$filename.'"');

		// Write file to the browser
		$writer->save('php://output');

		// redirect($data['redirectUrl']);
	}
	 
	public function import($filepath)
	{
		if (empty($filepath))
		{
			return false;
		}

		try
		{
			$fileInfo = pathinfo($filepath);
			$fileExtension = strtolower($fileInfo['extension']);
			$reader = '';

			switch($fileExtension)
			{
				case 'csv':
					$reader = new csvReader();
					break;
				case 'xlsx':
					$reader = new xlsxReader();
					break;
				default:
					break;
			}

			if ($reader == '')
			{
				throw new Exception('Invalid file');
			}

			$reader->setReadDataOnly(true);

			return $reader->load($filepath)->getActiveSheet()->toArray();
		}
		catch(Exception $e)
		{
			return false;
		}
	}

	private function makeAlphabateRangeForExcelSheet()
	{
		$alphabates = range('A', 'Z');
		$tempAlphabates = $alphabates;

		foreach($tempAlphabates as $initialLetterIndex => $initialLetter)
		{
			foreach($tempAlphabates as $letter)
			{
				$alphabates[] = sprintf("%s%s", $initialLetter, $letter);
			}

			if ($initialLetterIndex == 1)
			{
				break;
			}
		}

		return $alphabates;
	}
}
