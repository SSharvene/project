<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;

class AssetSeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now()->toDateTimeString();

        $rows = [
            ['Laptop Dell Latitude 5440','Laptop','Dell','DL5440-001','Laptop pejabat untuk pegawai ICT',10,'Aktif','laptop_dell5440.jpg'],
            ['Monitor HP 24f','Monitor','HP','HP24F-021','Paparan IPS 24 inci Full HD',12,'Aktif','monitor_hp24f.jpg'],
            ['Pencetak LaserJet Pro M404dn','Pencetak','HP','LJ404DN-009','Printer laser hitam putih',5,'Aktif','printer_hp404.jpg'],
            ['PC Desktop Lenovo ThinkCentre M90t','Desktop','Lenovo','M90T-015','PC pejabat prestasi tinggi',8,'Aktif','lenovo_m90t.jpg'],
            ['Router MikroTik RB3011','Rangkaian','MikroTik','RB3011-007','Router pejabat HQ untuk VLAN',3,'Aktif','mikrotik_rb3011.jpg'],
            ['Projector Epson EB-X51','Peralatan Paparan','Epson','EBX51-003','Projektor mesyuarat dan latihan',2,'Aktif','projector_epson.jpg'],
            ['Kamera Web Logitech C920 HD','Aksesori','Logitech','C920HD-014','Kamera untuk mesyuarat dalam talian',6,'Aktif','logitech_c920.jpg'],
            ['Keyboard Logitech MK295','Aksesori','Logitech','MK295-101','Papan kekunci & tetikus tanpa wayar',15,'Aktif','keyboard_mk295.jpg'],
            ['Switch Cisco Catalyst 2960','Rangkaian','Cisco','C2960-010','Switch 24 port untuk lantai 8 HQ',4,'Aktif','cisco_2960.jpg'],
            ['UPS APC 1500VA','Kuasa','APC','APC1500-002','Bekalan kuasa sandaran server ICT',5,'Aktif','apc_1500.jpg'],
            ['Tablet Samsung Galaxy Tab A8','Tablet','Samsung','TABA8-006','Tablet untuk pegawai lapangan',7,'Aktif','tab_a8.jpg'],
            ['Headset Jabra Evolve 40','Audio','Jabra','EV40-012','Headset pejabat dengan mikrofon',10,'Aktif','jabra_ev40.jpg'],
            ['Printer Epson EcoTank L3250','Pencetak','Epson','L3250-005','Printer warna pejabat',4,'Aktif','epson_l3250.jpg'],
            ['CCTV Hikvision DS-2CD1043G0','Keselamatan','Hikvision','DS1043G0-008','Kamera keselamatan luar pejabat',6,'Aktif','hikvision_ds2cd.jpg'],
            ['External Hard Drive Seagate 2TB','Penyimpanan','Seagate','SEA2TB-020','Pemacu sandaran ICT',8,'Aktif','seagate_2tb.jpg'],
            ['Laptop HP ProBook 450 G9','Laptop','HP','HP450G9-017','Laptop baharu untuk staf pentadbiran',10,'Aktif','hp_probook450.jpg'],
            ['Scanner Canon DR-C240','Pengimbas','Canon','DRC240-004','Pengimbas dokumen A4 untuk HR',2,'Aktif','canon_drc240.jpg'],
            ['Server Dell PowerEdge T550','Server','Dell','T550-001','Server fail dalaman JAKOA ICT',1,'Aktif','dell_t550.jpg'],
            ['Access Point TP-Link EAP245','Rangkaian','TP-Link','EAP245-009','Wi-Fi Access Point pejabat HQ',10,'Aktif','tplink_eap245.jpg'],
            ['Desktop HP EliteDesk 800 G6','Desktop','HP','ED800G6-011','Komputer untuk staf operasi ICT',6,'Aktif','hp_elitedesk800.jpg'],
        ];

        $insert = [];
        foreach ($rows as $r) {
            $insert[] = [
                'nama_aset' => $r[0],
                'kategori'  => $r[1],
                'jenama'    => $r[2],
                'no_siri'   => $r[3],
                'keterangan'=> $r[4],
                'bilangan'  => $r[5],
                'status'    => $r[6],
                'gambar'    => $r[7],
                'created_at'=> $now,
                'updated_at'=> $now,
            ];
        }

        DB::table('assets')->insert($insert);
    }
}
