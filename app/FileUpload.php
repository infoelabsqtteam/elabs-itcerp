<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
use File;
use App\Helpers\SimpleImage;

class FileUpload extends Model
{
    protected $table = '';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [];

    /*******************************************************************
     * Function : Validate File Extension
     * Created By : Praveen Singh
     * Created On : 28-Dec-2019
     *******************************************************************/
    public function validateFileExtension($fileInputType, $request, $allowedExtension)
    {
        if ($request->hasFile($fileInputType)) {
            $file              = $request->file($fileInputType);
            $file_name         = trim($file->getClientOriginalName());
            $extract_ext       = pathinfo($file_name);
            $dirname           = $extract_ext['dirname'];
            $basename          = $extract_ext['basename'];
            $imagename         = $extract_ext['filename'];
            $image_extension   = strtolower($extract_ext['extension']);
        }
        if (!empty($allowedExtension) && !empty($image_extension) && in_array($image_extension, $allowedExtension)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function media_uploads($rootPath, $trfPath, $fileInputType, $request, $fileName)
    {

        $file_name = '';

        if ($request->hasFile($fileInputType)) {
            $file              = $request->file($fileInputType);
            $file_name         = trim($file->getClientOriginalName());
            $extract_ext       = pathinfo($file_name);
            $dirname           = $extract_ext['dirname'];
            $basename          = $extract_ext['basename'];
            $imagename         = $extract_ext['filename'];
            $image_extension   = strtolower($extract_ext['extension']);
            $file_name         = trim($fileName . '.' . $image_extension);
            $upload_path       = $rootPath . $trfPath;
            if (!is_dir($upload_path)) {
                mkdir($upload_path, 0777, true);
            }
            if ($file->move($upload_path, $file_name)) {
                return $file_name;
            }
        }
        return $file_name;
    }

    /**
     * upload the specified image in storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function uploadSignature($user_id, $requestData)
    {

        $deptNames = array();
        $division_name = $user_name = '';

        if (!empty($requestData->file('user_signature'))) {
            $file     = $requestData->file('user_signature');
            $userData = DB::table('users')
                ->join('divisions', 'divisions.division_id', '=', 'users.division_id')
                ->join('users_department_detail', 'users_department_detail.user_id', '=', 'users.id')
                ->join('department_product_categories_link', 'department_product_categories_link.department_id', '=', 'users_department_detail.department_id')
                ->join('departments', 'departments.department_id', '=', 'department_product_categories_link.department_id')
                ->select('users.name', 'divisions.division_name', 'departments.department_name as dept_name')
                ->where('users.id', '=', $user_id)->get();
            if (!empty($userData)) {
                foreach ($userData as $key => $value) {
                    $deptNames[$key] = substr($value->dept_name, 0, 1);
                    $division_name   = $value->division_name;
                    $user_name       = $value->name;
                }
                $division_name     = strtolower(substr($value->division_name, 0, 1));
                $dept_name     = strtolower(str_replace(',', '', implode(',', $deptNames)));
            }
            $file_name      = strtolower(preg_replace('/[_]+/', '_', preg_replace("/[^a-zA-Z]/", "_", $user_name))) . '_' . $division_name . $dept_name . '.' . $file->getClientOriginalExtension();
            $extract_ext     = pathinfo($file_name);
            $dirname         = $extract_ext['dirname'];
            $basename         = $extract_ext['basename'];
            $imagename        = $extract_ext['filename'];
            $image_extension     = $extract_ext['extension'];
            $image_path     = public_path() . '/images/signatures/';

            if (!file_exists($image_path)) {
                mkdir($image_path, 0777, true);
            }
            if ($file->move($image_path, $file_name)) {
                $image = new SimpleImage();
                $image->load($image_path . $file_name);
                //Item Image size
                $image->resizeToHeight(80);
                $image_thumb = $imagename . '.' . $image_extension;
                $image->save($image_path . $image_thumb);
                $src_Path = url('') . '/public/images/signatures/' . $image_thumb;
                return array($image_thumb, $src_Path);
            }
        }
    }

    /**
     * upload the specified image in storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function removeUploadedSignature($item_id, $item_image)
    {
        $image_path = public_path() . '/images/signatures/' . $item_image;
        $src_Path   = url('') . '/public/images/default-item.jpeg';
        if (array_map('unlink', glob($image_path . "*"))) {
            return $src_Path;
        }
        return false;
    }

    /*******************************************************************
     * Function : Validate Customer Sales Executive Csv File
     * Created By : Praveen Singh
     * Created On : 28-Dec-2021
     *******************************************************************/
    public function validateCustomerSalesExecutiveCsvFile($csvFileData)
    {
        if (
            !empty($csvFileData['header']) && count($csvFileData['header']) == '3'
            && in_array('SNO', $csvFileData['header'])
            && in_array('CUSTOMER CODE', $csvFileData['header'])
            && in_array('EMPLOYEE CODE', $csvFileData['header'])
        ) {
            return true;
        } else {
            return false;
        }
    }

    /*******************************************************************
     * Function : Validate Purchase Order Csv File
     * Created By : Praveen Singh
     * Created On : 25-Feb-2022
     *******************************************************************/
    public function validatePurchaseOrderCsvFile($csvFileData)
    {
        if (
            !empty($csvFileData['header']) && count($csvFileData['header']) == '4'
            && in_array('SNO', $csvFileData['header'])
            && in_array('ORDERNO', $csvFileData['header'])
            && in_array('PONO', $csvFileData['header'])
            && in_array('PODATE', $csvFileData['header'])
        ) {
            return true;
        } else {
            return false;
        }
    }
}
