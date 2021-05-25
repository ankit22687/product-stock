<?php

namespace App\Http\ImportValidator;

use Exception;
use App\Http\Traits\CsvHelper;
use Illuminate\Support\Facades\Validator;

class ProductImportValidator
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
        'code_column'      => 'required',
        'name_column' => 'required',
        'description_column'  => 'required',
        'code.*'         => 'required',
        'name.*'         => 'required',
        'description.*'  => 'required'
    ];

    /**
     * Constructor for ProductImportValidator
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

        // Find code column
        $code_column = $this->getColumnNameByValue($header, 'code');

        // Find name column
        $name_column = $this->getColumnNameByValue($header, 'name');

        // Find description column
        $description_column = $this->getColumnNameByValue($header, 'description');

        // Get second row of the file as the first data row
        $csvData = $this->mergeHeaderWithRows($data, $header);

        $anCode = $asName = $asDescripton = [];
        if (is_array($csvData) && count($csvData) > 0) {
            $i = 0;
            
            foreach ($csvData as $data) {
                $anCode[$i] = $data['code'];
                $asName[$i] = $data['name'];
                $asDescripton[$i] = $data['description'];
                $i++;
            }
        }

        // Build our validation array
        $validation_array = [
            'code_column' => $code_column,
            'name_column' => $name_column,
            'description_column' => $description_column,
            'code' => $anCode,
            'name' => $asName,
            'description' => $asDescripton
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
