<?php

namespace App\Http\Requests\Media;

use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $files = request()->file();
        $key = is_array($files) && count($files) ? array_keys($files)[0] : $files;

        $accepted = request()->has('accepted') && !empty(request()->accepted) ? request()->accepted : 'jpg,jpeg,png,doc,docx,xls,xlsx,pdf';
        $isMultiple = is_array($files[$key]);
        $mimes = implode(',', $this->determineMimetypes(explode(',', $accepted)));
        if ($isMultiple) {
            return [
                'attachable' => ['nullable', 'array'],
                $key => ['required', 'array'],
                $key . '.*' => ['required', 'file', 'max:3000', 'mimes:' . $accepted, 'mimetypes:' . $mimes]
            ];
        } else {
            return [
                $key => ['required', 'file', 'max:3000', 'mimes:' . $accepted, 'mimetypes:' . $mimes]
            ];
        }
    }

    private function determineMimetypes(array $extensions)
    {
        $mimes = [];
        foreach ($extensions as $extension) {
            switch (strtolower($extension)) {
                case 'jpg':
                    array_push($mimes, 'image/jpg');
                    break;
                case 'jpeg':
                    array_push($mimes, 'image/jpeg');
                    break;
                case 'png':
                    array_push($mimes, 'image/png');
                case 'tif':
                    array_push($mimes, 'image/tiff');
                case 'tiff':
                    array_push($mimes, 'image/tiff');
                case 'svg':
                    array_push($mimes, 'image/svg+xml');
                    break;
                case 'webp':
                    array_push($mimes, 'image/webp');
                case 'doc':
                    array_push($mimes, 'application/msword');
                    break;
                case 'docx':
                    array_push($mimes, 'application/vnd.openxmlformats-officedocument.wordprocessingml.document');
                    break;
                case 'xls':
                    array_push($mimes, 'application/vnd.ms-excel');
                    break;
                case 'xlsx':
                    array_push($mimes, 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
                    break;
                case 'pdf':
                    array_push($mimes, 'application/pdf');
                    break;
                default:
                    abort(404, 'L\'extension ' . $extension . ' n\'est pas prise en charge.');
                    break;
            }
        }
        return $mimes;
    }
}