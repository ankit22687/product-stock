<?php

namespace App\Http\ImportValidator;

use Exception;
use App\Http\Traits\CsvHelper;
use Illuminate\Support\Facades\Validator;

class StockImportValidator
{
    use CsvHelper;
    /**
     * Validator object
     * @var Illuminate\Support\Facades\Validator
     */
    private $validator;

    /**
     * Validation rules for CsvImport
     *
     */
    private $rules = [
        'product_code_column'      => 'required',
        'on_hand_column' => 'required',
        'production_date_column'  => 'required',
        'product_code.*'=>'required',
        'on_hand.*'=>'required|numeric',
        'production_date.*'=>'required|date_format:d/m/y'
    ];

    /**
     * Constructor for StockImportValidator
     * @param Illuminate\Support\Facades\Validator $validator
     */
    public function __construct(Validator $validator)
    {
        $this->validator = $validator;
    }

    /**
     * Validation method
     * @param \Symfony\Component\HttpFoundation\File\UploadedFile
     * @return \Illuminate\Validation\Validator $validator
     */
    public function validate($csvFilePath)
    {
        // Line endings fix
        ini_set('auto_detect_line_endings', true);

        // Open file into memory
        if (fopen($csvFilePath, 'r') === false) {
            throw new Exception('File cannot be opened for reading');
        }

        $data = array_map('str_getcsv', file($csvFilePath));
        
        $header = array_shift($data);

        $header = $this->removeSpecialCharacter($header);

        // Find product code column
        $product_code_column = $this->getColumnNameByValue($header, 'product_code');

        // Find on hand column
        $on_hand_column = $this->getColumnNameByValue($header, 'on_hand');

        // Find production_date column
        $production_date_column = $this->getColumnNameByValue($header, 'production_date');

        // Get second row of the file as the first data row
        $csvData = $this->mergeHeaderWithRows($data, $header);

        $anProductCode = $anOnHand = $asProductionDate = [];
        if (is_array($csvData) && count($csvData) > 0) {
            $i = 0;
            
            foreach ($csvData as $data) {
                $anProductCode[$i] = $data['product_code'];
                $anOnHand[$i] = $data['on_hand'];
                $asProductionDate[$i] = $data['production_date'];
                $i++;
            }
        }

        // Build our validation array
        $validation_array = [
            'product_code_column' => $product_code_column,
            'on_hand_column' => $on_hand_column,
            'production_date_column' => $production_date_column,
            'product_code' => $anProductCode,
            'on_hand' => $anOnHand,
            'production_date' => $asProductionDate
        ];

        // Return validator object
        return $this->validator::make($validation_array, $this->rules);
    }

    /**
     * Attempts to find a value in array or returns empty string
     * @param array  $array hay stack we are searching
     * @param string $key
     *
     */
    private function getColumnNameByValue($array, $value)
    {
        return in_array($value, $array)? $value : '';
    }
}
