<?php


namespace App\Console\Commands\Other;


use App\Enum\Table;
use App\Services\BaiDu\Trans;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Mockery\Exception;

class loadGeoData extends Command
{
    /**
     * 命令
     *
     * @var string
     */
    protected $signature = 'load.geo';

    /**
     * 命令行的概述。
     * @var string
     */
    protected $description = '加载地区信息数据';

    /**
     * 运行命令。
     *
     * @return mixed
     */
    public function handle()
    {
        $this->trans();
    }

    protected function loadGeoData()
    {
        if (DB::table(Table::GEO)->count()) {
            $this->error('数据库中已存在数据！');
            die;
        }
        $file_name = resource_path() . '/geotargets-2020-09-08.csv';
        $handle = @fopen($file_name, 'r');
        if ($handle) {
            $title = fgetcsv($handle, 300);
            if ($title) {
                $sum = 0;
                $i = 0;
                $insert_data = [];
                while(($row = fgetcsv($handle)) !== false){
                    # 每 100 条数据执行一次插入数据
                    if ($i >= 100) {
                        $this->insertData($insert_data, $sum);
                        $i = 0;
                        $insert_data = [];
                    }
                    if ($row) {
                        $insert_data[] = [
                            'criteria_id' => $row[0],
                            'name_en' => $row[1] ?: '',
                            'canonical_name_en' => $row[2],
                            'parent_id' => $row[3] ?: 0,
                            'country_code' => $row[4],
                            'target_type_en' => $row[5],
                            'status_en' => $row[6]
                        ];
                        $i++;
                    } else {
                        Log::info('数据第 ' . $sum . ' 条。');
                        Log::info($row);
                    }
                    $sum++;
                }
                # 插入最后一次数据
                $this->insertData($insert_data, $sum);
            }
            fclose($handle);
        }
    }
    protected function trans()
    {
        $data = DB::table(Table::GEO)
            ->select('target_type_en')->groupBy('target_type_en')
            ->get()->toArray();
        $data = objToArray($data);
        if ($data) {
            $types = array_column($data, 'target_type_en');
            $query = implode('<<<>>>', $types);
            $res = (new Trans())->run($query);
            dd($res);
        }
    }

    protected function insertData($data, $sum)
    {
        try{
            DB::table(Table::GEO)->insert($data);
            echo date('Ymd-H:i:s : ') . $sum . "\n";
        } catch (Exception $e) {
            dd('插入数据中断，已扫描' . $sum . '条。');
        }
    }
}
