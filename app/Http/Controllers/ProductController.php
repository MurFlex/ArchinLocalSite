<?php

namespace App\Http\Controllers;

use App\Models\ApplicableDevice;
use App\Models\Device;
use App\Models\InapplicableDevice;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function index($id)
    {
        $decoding = [
            'device_id'         => 'Номер СИ в реестре',
            'regNumber'         => 'Регистрационный номер СИ',
            'mitypeNumber'      => 'Номер в реестре утвержденого типа СИ',
            'mitypeType'        => 'Тип СИ',
            'applicable'        => 'СИ пригодно',
            'mitypeTitle'       => 'Наименование утвержденного типа',
            'rankCode'          => 'Код разряда эталона в ГПС',
            'rankTitle'         => 'Наименование разряда эталона в ГПС',
            'schemaTitle'       => 'Наименование поверочной схемы или методики',
            'singleMi'          => 'Знак поверки на СИ',
            'manufactureNum'    => 'Заводской/серийный номер СИ',
            'manufactureYear'   => 'Год выпуска СИ',
            'modification'      => 'Модификатор СИ',
            'organization'      => 'Наименование организации-поверителя',
            'signCipher'        => 'Условный шифр зака поверителя',
            'miOwner'           => 'ЮЛ(ФЛ), передавшее СИ на поверку',
            'vrfDate'           => 'Дата поверки',
            'validDate'         => 'Поверка действительна до',
            'vriType'           => 'Тип поверки',
            'docTitle'          => 'Наименование документа на основании которого выполнена поверка',
            'certNum'           => 'Номер свидетельства/выписки',
            'stickerNum'        => 'Номер наклейки',
            'noticeNum'         => 'Номер извещения/выписки',
            'signPass'          => 'Знак поверки в паспорте',
            'signMi'            => 'Знак поверки на СИ',
//            'mitypeURL'         => 'URL карточки типа СИ',
        ];

        if(Device::where(
            'device_id', '=', $id)->first()->miInfoType == 'singleMIDevice') {
            $device = Device::where(
                'devices.device_id', '=', $id)->leftJoin(
                    'vri_infos', function($join) {
                $join->on('devices.device_id', '=', 'vri_infos.device_id');
            })->leftJoin('categories', function($join) {
                $join->on('devices.category_id', '=', 'categories.category_id');
            })->leftJoin('singlemi_devices', function($join) {
                $join->on('devices.device_id', '=', 'singlemi_devices.device_id');
            })->first()->toArray();
        } elseif(Device::where(
            'device_id', '=', $id)->first()->miInfoType == 'etaMIDevice') {
            $device = Device::where(
                'devices.device_id', '=', $id)->leftJoin(
                    'vri_infos', function($join) {
                $join->on('devices.device_id', '=', 'vri_infos.device_id');
            })->leftJoin('categories', function($join) {
                $join->on('devices.category_id', '=', 'categories.category_id');
            })->leftJoin('etami_devices', function($join) {
                $join->on('devices.device_id', '=', 'etami_devices.device_id');
            })->first()->toArray();
        } elseif(Device::where(
            'device_id', '=', $id)->first()->miInfoType == 'partyMIDevice') {
            $device = Device::where(
                'devices.device_id', '=', $id)->leftJoin(
                    'vri_infos', function($join) {
                $join->on('devices.device_id', '=', 'vri_infos.device_id');
            })->leftJoin('categories', function($join) {
                $join->on('devices.category_id', '=', 'categories.category_id');
            })->leftJoin('partymi_devices', function($join) {
                $join->on('devices.device_id', '=', 'partymi_devices.device_id');
            })->first()->toArray();
        }

        if(count(ApplicableDevice::where('device_id', '=', $id)->get()) > 0) {
            $device += ['applicable' => 'Да'];
            $device = array_merge(
                $device, ApplicableDevice::where(
                    'device_id', '=', $id)->first()->toArray());;
        } else {
            $device += ['applicable' => 'Нет'];
            $device = array_merge(
                $device, InapplicableDevice::where(
                    'device_id', '=', $id)->first()->toArray());
        }

        return view('device_info', [
            'device' => $device,
            'decoding' => $decoding
        ]);
    }
}
