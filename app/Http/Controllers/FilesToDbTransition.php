<?php

namespace App\Http\Controllers;

use App\Models\ApplicableDevice;
use App\Models\Category;
use App\Models\Device;
use App\Models\InapplicableDevice;
use App\Models\singleMIDevice;
use App\Models\PartymiDevice;
use App\Models\EtamiDevice;
use App\Models\Company;
use App\Models\VriInfo;

class FilesToDbTransition extends Controller
{
    /**
     * transforms name of company in case we can store them in same way
     *
     * @param $str
     * @return bool|false|string|string[]|null
     */
    public function transformate($str)
    {
        $deletePhrases = [
            '«' => ' ',
            "'" => ' ',
            '»' => ' ',
            '>' => ' ',
            '<' => ' ',
            '"' => ' ',
//            '-' => '',
            ',' => ' ',
            '.' => ' ',
            '”' => ' ',
            'АКАДЕМ.' => 'АК.',
            'АКАДЕМИКА' => 'АК.',
            'РОССИЙСКОЙ АКАДЕМИИ НАУК' => 'РАН',
            'ФЕДЕРАЛЬНОГО ИССЛЕДОВАТЕЛЬСКОГО ЦЕНТРА' => 'ФИЦ',
            'НАЦИОНАЛЬНЫЙ НАУЧНЫЙ ЦЕНТР' => 'ННЦ',
            'ИМЕНИ' => 'ИМ.',
            'Ф-Л' => 'ФИЛИАЛ',
            'ФИЛ.' => 'ФИЛИАЛ',
            'ОТКРЫТОЕ АКЦИОНЕРНОЕ ОБЩЕСТВО' => 'ОАО',
            'ПУБЛИЧНОЕ АКЦИОНЕРНОЕ ОБЩЕСТВО' => 'ПАО',
            'АКЦИОНЕРНОЕ ОБЩЕСТВО' => 'АО',
            'ОБЩЕСТВО С ОГРАНИЧЕННОЙ ОТВЕТСТВЕННОСТЬЮ' => 'ООО',
            'ФИЗИЧЕСКОЕ ЛИЦО' => 'ФЛ',
            'ФИЗ. ЛИЦО' => 'ФЛ',
            'ФИЗ ЛИЦО' => 'ФЛ',
            'ФИЗ.ЛИЦО' => 'ФЛ',
            'ЮРИДИЧЕСКОЕ ЛИЦО' => 'ЮЛ',
            'ЮР. ЛИЦО' => 'ЮЛ',
            'ЮР.ЛИЦО' => 'ЮЛ',
            'ЮР ЛИЦО' => 'ЮЛ',
            'ФЕДЕРАЛЬНОЕ ГОСУДАРСТВЕННОЕ БЮДЖЕТНОЕ ОБРАЗОВАТЕЛЬНОЕ УЧРЕЖДЕНИЕ' => 'ФГБОУ',
            'ВЫСШЕГО ОБРАЗОВАНИЯ' => 'ВО',
            'ЦЕНТР ГИГИЕНЫ И ЭПИДЕМИОЛОГИИ' => 'ЦГИЭ',
            'НАУЧНО-ПРОИЗВОДСТВЕННЫЙ ЦЕНТР' => 'НПЦ',
            'НАУЧНО ПРОИЗВОДСТВЕННАЯ ФИРМА' => 'НПФ',
            'НАУЧНО-ПРОИЗВОДСТВЕННАЯ КОМПАНИЯ' => 'НПК',
            'НАУЧНО-ПРОИЗВОДСТВЕННОЕ ПРЕДПРИЯТИЕ' => 'НПП',
            'НАУЧНО-ПРОИЗВОДСТВЕННОЕ ОБЪЕДИНЕНИЕ' => 'НПО',
            'ФЕДЕРАЛЬНОЕ ГОСУДАРСТВЕННОЕ КАЗЕННОЕ УЧРЕЖДЕНИЕ' => 'ФГКУ',
            'РОССИЙСКИЙ ФЕДЕРАЛЬНЫЙ ЯДЕРНЫЙ ЦЕНТР' => 'РФЯЦ',
            'ВСЕРОССИЙСКИЙ НАУЧНО-ИССЛЕДОВАТЕЛЬСКИЙ ИНСТИТУТ ТЕХНИЧЕСКОЙ ФИЗИКИ' => 'ВНИИТФ',
            'ФЕДЕРАЛЬНОЕ ГОСУДАРСТВЕННОЕ УНИТАРНОЕ ПРЕДПРИЯТИЕ' => 'ФГУП',
            'ФЕДЕРАЛЬНОЕ БЮДЖЕТНОЕ УЧРЕЖДЕНИЕ' => 'ФБУ',
            'ФЕДЕРАЛЬНОЕ ГОСУДАРСТВЕННОЕ БЮДЖЕТНОЕ УЧРЕЖДЕНИЕ' => 'ФГБУ',
            'ФЕДЕРАЛЬНОГО ГОСУДАРСТВЕННОГО БЮДЖЕТНОГО УЧРЕЖДЕНИЯ' => 'ФГБУ',
            'ОБЛАСТНОЕ ГОСУДАРСТВЕННОЕ БЮДЖЕТНОЕ УЧРЕЖДЕНИЕ' => 'ОГБУ',
            'ГОСУДАРСТВЕННОЕ БЮДЖЕТНОЕ УЧРЕЖДЕНИЕ' => 'ГБУ',
            'ГОСУДАРСТВЕННОГО БЮДЖЕТНОГО УЧРЕЖДЕНИЯ' => 'ГБУ',
            'ФЕДЕРАЛЬНОГО БЮДЖЕТНОГО УЧРЕЖДЕНИЯ' => 'ФБУ',
            'УПРАВЛЕНИЕ ФЕДЕРАЛЬНОЙ СЛУЖБЫ БЕЗОПАСНОСТИ' => 'УФСБ',
        ];

        $str = mb_strtoupper($str['vriInfo']['miOwner'], 'UTF-8');

        if(strpos($str, 'ИНН')) {
            if(strstr($str, ' ИНН ', True)){
                $str = strstr(
                    $str, ' ИНН ', True);
            } elseif(strstr($str, ' ИНН', True )) {
                $str = strstr(
                    $str, ' ИНН', True );
            }
        }

        if(strpos($str, '(')) {
            $str = preg_replace("/\\(.+\\)/m", '', $str);
        }

        if(strpos($str, '–' )) $str = str_replace('–', '-', $str);

        if(strpos($str, ' - ') || strpos($str, ' -') || strpos($str, '- ')) {
            $str = strstr($str, ' - ', '-');
            $str = strstr($str, ' -', '-');
            $str = strstr($str, '- ', '-');
        }

        $str = str_replace(
            array_keys($deletePhrases),
            $deletePhrases,
            mb_strtoupper($str, 'UTF-8'));

        $str = preg_replace('| +|', ' ', trim($str));
        return $str;
    }

    /**
     * Contoller to copy data from files to database
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function index()
    {
            $dir = '../app/Logs/';
            $files = scandir($dir);

//            $categories_data = json_decode(
//                file_get_contents('../app/Helpers/new_result.json'),
//                1);
//
//            foreach ($categories_data as $category => $data) {
//                if (!count(Category::where('category_id', '=', $category)->get())) {
//                    $category_db = new Category();
//                    $category_db->category_id = $category;
//                    $category_db->category_title = $data['name'];
//                    $category_db->category_type = $data['type'];
//                    $category_db->save();
//                }
//            }

            $filtered_files = array();
            foreach (array_slice($files, 2) as $file) {
                $exploded_array = explode(" ", $file);
                if (end($exploded_array) !== "Started.json"
                    && end($exploded_array) !== 'Ended.json') {
                    $filtered_files[] = $file;
                }
            }

            $data = [];

            foreach ($filtered_files as $file) {
                $data += json_decode(file_get_contents(
                    $dir . $file), 1);
            }

            foreach ($data as $id => $element) {
                if (isset($element['vriInfo']['miOwner'])) {
                    $element = $this->transformate($element);
                    if (!count(Company::where('company_name', '=', $element)->get())) {
                        $company = new Company();
                        $company->company_name = trim($element);
                        $company->save();
                    }
                }
            }

            foreach ($data as $id => $element) {
                if (is_array($element)) {
                    if (!count(Device::where('device_id', '=', $id)->get())) {
                        foreach ($element as $index => $item) {
                            if ($index == 'miInfo') {
                                $classname = key($item) . 'Device';
                                $device = new Device();
                                $device->device_id = $id;
                                if (isset($element['vriInfo']['miOwner'])) {
                                    $company_id = isset(Company::where('company_name', '=', $this->transformate($element))->first()->company_id) ?
                                        Company::where('company_name', '=', $this->transformate($element))->first()->company_id : null;
                                    $device->company_id = $company_id;
                                } else {
                                    $company_id = null;
                                }
                                $device->miInfoType = $classname;
                                if (isset($element['vriInfo']['applicable'])) {
                                    $applicable = new ApplicableDevice();
                                    $applicable->device_id = $id;
                                    $applicable->company_id = $company_id;
                                    $applicable->certNum = $element['vriInfo']['applicable']['certNum'];
                                    $applicable->signPass = $element['vriInfo']['applicable']['signPass'];
                                    $applicable->signMi = $element['vriInfo']['applicable']['signMi'];
                                    $applicable->save();
                                    $device->applicable = 'Y';
                                } elseif (isset($element['vriInfo']['inapplicable'])) {
                                    $inApplicable = new InapplicableDevice();
                                    $inApplicable->device_id = $id;
                                    $inApplicable->company_id = $company_id;
                                    $inApplicable->noticeNum = $element['vriInfo']['inapplicable']['noticeNum'];
                                    $inApplicable->save();
                                    $device->applicable = 'N';
                                }
                                if ($classname == 'singleMIDevice') {
                                    $singleMiDevice = new SinglemiDevice();
                                    $device->category_id = isset($item['singleMI']['mitypeNumber']) ? $item['singleMI']['mitypeNumber'] : null;
                                    $device->mitypeType = isset($item['singleMI']['mitypeType']) ? $item['singleMI']['mitypeType'] : null;
                                    $device->modification = isset($item['singleMI']['modification']) ? $item['singleMI']['modification'] : null;
                                    $singleMiDevice->device_id = $id;
                                    $singleMiDevice->mitypeNumber = isset($item['singleMI']['mitypeNumber']) ? $item['singleMI']['mitypeNumber'] : null;
                                    $singleMiDevice->mitypeURL = isset($item['singleMI']['mitypeURL']) ? $item['singleMI']['mitypeURL'] : null;
                                    $singleMiDevice->mitypeType = isset($item['singleMI']['mitypeType']) ? $item['singleMI']['mitypeType'] : null;
                                    $singleMiDevice->mitypeTitle = isset($item['singleMI']['mitypeTitle']) ? $item['singleMI']['mitypeTitle'] : null;
                                    $singleMiDevice->manufactureNum = isset($item['singleMI']['manufactureNum']) ? $item['singleMI']['manufactureNum'] : null;
                                    $singleMiDevice->inventoryNum = isset($item['singleMI']['inventoryNum']) ? $item['singleMI']['inventoryNum'] : null;
                                    $singleMiDevice->manufactureYear = isset($item['singleMI']['manufactureYear']) ? $item['singleMI']['manufactureYear'] : null;
                                    $singleMiDevice->modification = isset($item['singleMI']['modification']) ? $item['singleMI']['modification'] : null;
                                    $singleMiDevice->save();
                                } elseif ($classname == 'partyMIDevice') {
                                    $partyMiDevice = new partyMIDevice();
                                    $device->category_id = isset($item['partyMI']['mitypeNumber']) ? $item['partyMI']['mitypeNumber'] : null;
                                    $device->mitypeType = isset($item['partyMI']['mitypeType']) ? $item['partyMI']['mitypeType'] : null;
                                    $device->modification = isset($item['partyMI']['modification']) ? $item['partyMI']['modification'] : null;
                                    $partyMiDevice->device_id = $id;
                                    $partyMiDevice->mitypeNumber = isset($item['partyMI']['mitypeNumber']) ? $item['partyMI']['mitypeNumber'] : null;
                                    $partyMiDevice->mitypeURL = isset($item['partyMI']['mitypeURL']) ? $item['partyMI']['mitypeURL'] : null;
                                    $partyMiDevice->mitypeType = isset($item['partyMI']['mitypeType']) ? $item['partyMI']['mitypeType'] : null;
                                    $partyMiDevice->modification = isset($item['partyMI']['modification']) ? $item['partyMI']['modification'] : null;
                                    $partyMiDevice->quantity = isset($item['partyMI']['quantity']) ? $item['partyMI']['quantity'] : null;
                                    $partyMiDevice->mitypeTitle = isset($item['partyMI']['mitypeTitle']) ? $item['partyMI']['mitypeTitle'] : null;
                                    $partyMiDevice->save();
                                } elseif ($classname == 'etaMIDevice') {
                                    $etaMiDevice = new etaMIDevice();
                                    $device->category_id = isset($item['etaMI']['mitypeNumber']) ? $item['etaMI']['mitypeNumber'] : null;
                                    $device->mitypeType = isset($item['etaMI']['mitypeType']) ? $item['etaMI']['mitypeType'] : null;
                                    $device->modification = isset($item['etaMI']['modification']) ? $item['etaMI']['modification'] : null;
                                    $etaMiDevice->device_id = $id;
                                    $etaMiDevice->regNumber = isset($item['etaMI']['regNumber']) ? $item['etaMI']['regNumber'] : null;
                                    $etaMiDevice->mitypeNumber = isset($item['etaMI']['mitypeNumber']) ? $item['etaMI']['mitypeNumber'] : null;
                                    $etaMiDevice->mitypeURL = isset($item['etaMI']['mitypeURL']) ? $item['etaMI']['mitypeURL'] : null;
                                    $etaMiDevice->mitypeType = isset($item['etaMI']['mitypeType']) ? $item['etaMI']['mitypeType'] : null;
                                    $etaMiDevice->mitypeTitle = isset($item['etaMI']['mitypeTitle']) ? $item['etaMI']['mitypeTitle'] : null;
                                    $etaMiDevice->manufactureNum = isset($item['etaMI']['manufactureNum']) ? $item['etaMI']['manufactureNum'] : null;
                                    $etaMiDevice->manufactureYear = isset($item['etaMI']['manufactureYear']) ? $item['etaMI']['manufactureYear'] : null;
                                    $etaMiDevice->modification = isset($item['etaMI']['modification']) ? $item['etaMI']['modification'] : null;
                                    $etaMiDevice->rankCode = isset($item['etaMI']['rankCode']) ? $item['etaMI']['rankCode'] : null;
                                    $etaMiDevice->rankTitle = isset($item['etaMI']['rankTitle']) ? $item['etaMI']['rankTitle'] : null;
                                    $etaMiDevice->schemaTitle = isset($item['etaMI']['schemaTitle']) ? $item['etaMI']['schemaTitle'] : null;
                                    $etaMiDevice->save();
                                }
                                    $device->save();
                            } elseif ($index == 'vriInfo') {
                                $vriInfo = new VriInfo();
                                $vriInfo->device_id = $id;
                                $vriInfo->organization = isset($item['organization']) ? $item['organization'] : null;
                                $vriInfo->signCipher = isset($item['signCipher']) ? $item['signCipher'] : null;
                                $vriInfo->miOwner = isset($item['miOwner']) ? $this->transformate($element) : null;
                                $vriInfo->vrfDate = isset($item['vrfDate']) ? $item['vrfDate'] : null;
                                $vriInfo->validDate = isset($item['validDate']) ? $item['validDate'] : null;
                                $vriInfo->vriType = isset($item['vriType']) && $item['vriType'] == 1 ? 'Первичная' : 'Периодическая';
                                $vriInfo->applicable = isset($item['applicable']) ? 'Y' : 'N';
                                $vriInfo->save();
                            } elseif ($index == 'means') {
                                break;
                            }
                        }
                    } else {
                        continue;
                    }
                }
            }

        return Redirect('/');
    }
}
