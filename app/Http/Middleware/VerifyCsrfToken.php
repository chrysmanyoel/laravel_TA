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
    protected $except = ['/register','/login','/userhome','/updateuser','/adminhome','/ubahstatus','/getallsalon','/getallsalonuser','/insertlayanansalon','/getallkategori','/insertsalon','/getrole','/getuserswithsalon','/getidsalon','/getpegawai','/insertpegawai','/insertdetailpegawai','/getsaldouser','/getidpegawai','/getlayanansalon','/getuser','/getlayananwithuser','/getlayanansalondetail','/insertbookingservice','/getuserswithsalondgnusername','/updatesalon','/getdatapegawai','/getlistbookingwithlayanan','/getlistbookingwithlayanansemua','/updatestatusbooking','/updatejadwalsalon','/getjadwalsalon','/getlistbookingwithlayananuser','/getlistbookingwithlayananuserselesai','/statussalon','/getpegawai_absen','/insertabsenpegawai','/getabsensipegawai','/getpegawai_halamanmember','/transaksiTopUp_bank','/daftariklan','/getiklan','/getiklan_admin','/getiklan_admin_acc','/terima_iklan','/getalliklansalon','/getallfavoritjoinuser','/deletefav','/insertfav','/autoselesai','/carisalon','/getkategori','/insertkategori','/getallidkategori','/getdatapegawai_tampil','/cancelsemuabooking','/kirim_OTP','/cek_email','/updatenewpass','/getcounttidakhadir','/getlistbookingwithlayanansemua','/getlistbookingsalon','/getjadwalsalon_set','/updatereschedule_customer','/updatestatusreschedule','/getquotalayanan','/getquotapegawai','/apakahPegawaiTsbSiap','/hitungTransaksiBerjalan','/isvalidbooking','/konfirm_kodepesanan','/insertharilibur','/getsemuauser','/block_unblock_user','/gettopupsaldobank','/tambahsaldo_user','/beri_penilaian','/tambah_kurang_administrasi','/getiklan_voucher','/insert_voucher','/getiklan_voucher_aktif','/beli_voucher','/getiklan_voucher_aktif_join_tr','/getRating','/tambahsaldo','/getjadwalsalon_another_day','/getListChatUser','/cekPesan','/getinfo_salon','/getallsalonuser_seeall','/getallsalonuser_terpopuler','/getallsalonuser_24jam','/getallsalonuser_selaludiskon','/changemap','/getidkat','/getnamakat','/delkategori','/getwithdraw','/getwithdraw_histori','/kurang_saldo_user','/gettopup_histori','/updateservice','/sendNotification_user','/sendNotification_salon','/insert_report','/sendNotification','/get_report','/getreport_histori','/update_status_report','/transaksi_terakhir','/getharilibur','/cancel_tolak_booking','/hapus_jadwallibur','/auto_cekharilibur','/hapus_layanan_salon','/updatestatus_voucher','/getlayanansalon_halamansalon','/auto_cekstatusvoucher','/update_status_pegawai','/getiklan_sisisalon','/getlistbooking_laporan_customer','/transaksi_terakhir_laporan','/report_terakhir_laporan','/laporan_salon_penjualan','/laporan_salon_keuntungan_penjualan','/laporan_salon_iklan'];
}
