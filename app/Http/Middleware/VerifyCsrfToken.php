<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array
     */
    protected $except = ['/register','/login','/userhome','/updateuser','/adminhome','/ubahstatus','/getallsalon','/getallsalonuser','/insertlayanansalon','/getallkategori','/insertsalon','/getrole','/getuserswithsalon','/getidsalon','/getpegawai','/insertpegawai','/insertdetailpegawai','/getsaldouser','/getidpegawai','/getlayanansalon','/getuser','/getlayananwithuser','/getlayanansalondetail','/insertbookingservice','/getuserswithsalondgnusername','/updatesalon','/getdatapegawai','/getlistbookingwithlayanan','/getlistbookingwithlayanansemua','/updatestatusbooking','/updatejadwalsalon','/getjadwalsalon','/getlistbookingwithlayananuser','/getlistbookingwithlayananuserselesai','/statussalon','/getpegawai_absen','/insertabsenpegawai','/getabsensipegawai','/getpegawai_halamanmember','/transaksiTopUp_bank','/daftariklan','/getiklan','/getiklan_admin','/getiklan_admin_acc','/terima_iklan','/getalliklansalon','/getallfavoritjoinuser','/deletefav','/insertfav','/autoselesai','/carisalon','/getkategori','/insertkategori','/getallidkategori','/getdatapegawai_tampil','/cancelsemuabooking','/kirim_OTP','/cek_email','/updatenewpass','/getcounttidakhadir','/getlistbookingwithlayanansemua','/getlistbookingsalon','/getjadwalsalon_set','/updatereschedule_customer','/updatestatusreschedule','/getquotalayanan','/getquotapegawai','/apakahPegawaiTsbSiap','/hitungTransaksiBerjalan','/isvalidbooking','/konfirm_kodepesanan','/insertharilibur'];
}
