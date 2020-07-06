<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Zakat;

class SendNotif extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'send:notif';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command to send notif email and sms';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $query = Zakat::where("status",2)->where("fl_send_notif",0);
        //->whereDate("created_at","<=",now()->subDays(3)->setTime(0, 0, 0)->toDateTimeString());
        foreach($query->get() as $row){

            $zakat = Zakat::find($row->id);
            if($zakat){
                $zakat->fl_send_notif = 1;
                $zakat->save();
            }

            if($row->phone || $row->email){
                try {
                    if($row->phone){
                        $curl = curl_init();
                        curl_setopt_array($curl, array(
                            CURLOPT_RETURNTRANSFER => 1,
                            CURLOPT_URL => 'http://api.nusasms.com/api/v3/sendsms/plain',
                            CURLOPT_POST => true,
                            CURLOPT_POSTFIELDS => array(
                                'user' => 'obuyls_api',
                                'password' => 'p4Y65Se',
                                'SMSText' => 'Assalamualaikum Bpk/Ibu Yth, pembayaran zakat anda sudah kami terima. Terimakasih atas kepercayaannya. Laporan Penerimaan Zakat klik bit.ly/upzismbrReport',
                                'GSM' => $row->phone
                            )
                        ));
            
                        $resp = curl_exec($curl);
            
                        if (!$resp) {
                            die('Error: "' . curl_error($curl) . '" - Code: ' . curl_errno($curl));
                        } else {
                            // header('Content-type: text/xml'); /*if you want to output to be an xml*/
                            // return $resp;
                        }
                        curl_close($curl);
                    }

                    if($row->email){
                        try {
                            Mail::send('mails.receipt', [], function($message) use ($row) {
                                $message->to($row->email, $row->name)
                                    ->subject("Tanda Terima Zakat")
                                    ->from('no-reply@zakat.mbr.or.id','UPZIS MBR');
                            });
                        } catch (\Exception $e) {
                            continue;
                        }
                    }

                } catch (\Exception $e) {
                    continue;
                }
            }
        }

    }
}
