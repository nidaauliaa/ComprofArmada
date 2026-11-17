<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Halaman "Tentang Kami" (satu baris data).
 * Teks multi bahasa disimpan sebagai {"id": "...", "en": "...", "zh": "..."}.
 * Kalau admin belum menyimpan apa pun, halaman memakai isi bawaan di defaults().
 */
class AboutPage extends Model
{
    protected $fillable = ['content', 'director_photo'];

    protected $casts = ['content' => 'array'];

    public static function current(): self
    {
        try {
            return static::query()->first() ?? new static();
        } catch (\Throwable $e) {
            return new static(); // tabel belum dimigrasi
        }
    }

    /** Data tersimpan; kunci yang belum pernah disimpan memakai isi bawaan. */
    public function data(): array
    {
        return array_merge(static::defaults(), $this->content ?? []);
    }

    public function photoUrl(): ?string
    {
        return $this->director_photo ? asset('storage/'.$this->director_photo) : null;
    }

    // ---------- helper tampilan ----------

    /** Teks untuk bahasa aktif; kalau kosong pakai Indonesia. */
    public static function pick($v, ?string $locale = null): string
    {
        if (! is_array($v)) {
            return trim((string) $v);
        }
        $locale = $locale ?: app()->getLocale();
        $x = trim((string) ($v[$locale] ?? ''));

        return $x !== '' ? $x : trim((string) ($v['id'] ?? ''));
    }

    /** Aman XSS: teks di-escape dulu, lalu **tebal** dan *miring* diubah jadi HTML. Pakai dengan {!! !!}. */
    public static function rich(string $s): string
    {
        $s = e($s);
        $s = preg_replace('/\*\*(.+?)\*\*/su', '<strong>$1</strong>', $s);
        $s = preg_replace('/(?<!\*)\*(?!\*)(.+?)(?<!\*)\*(?!\*)/su', '<em>$1</em>', $s);

        return $s;
    }

    /** Pisah paragraf (dipisah baris kosong). */
    public static function paragraphs(string $s): array
    {
        return array_values(array_filter(array_map('trim', preg_split('/\R{2,}/u', trim($s)))));
    }

    /** Pisah per baris (untuk daftar misi). */
    public static function lines(string $s): array
    {
        return array_values(array_filter(array_map('trim', preg_split('/\R/u', trim($s)))));
    }

    /** "1962" untuk satu tahun, "1978 - 1981" untuk rentang. */
    public static function yearLabel(array $h): string
    {
        $from = (int) ($h['from'] ?? 0);
        $to = (int) ($h['to'] ?? 0);
        if ($from <= 0) {
            return '';
        }

        return ($to > 0 && $to !== $from) ? $from.' - '.$to : (string) $from;
    }

    /** Angka statistik. Kalau "since" diisi: tahun sekarang - since, mis. 64+ (otomatis ikut tahun berjalan). */
    public static function statValue(array $s): string
    {
        $since = (int) ($s['since'] ?? 0);
        if ($since > 0) {
            return max(0, (int) date('Y') - $since).'+';
        }

        return static::pick($s['value'] ?? '');
    }

    // ---------- isi bawaan (diambil dari prototype; EN & 中文 draf terjemahan, silakan dikoreksi di admin) ----------

    private static function ml(string $id, string $en = '', string $zh = ''): array
    {
        return ['id' => $id, 'en' => $en, 'zh' => $zh];
    }

    public static function defaults(): array
    {
        $m = fn (string $id, string $en = '', string $zh = '') => static::ml($id, $en, $zh);

        return [
            'hero_badge'  => $m('Corporate Profile', 'Corporate Profile', '企业简介'),
            'hero_title'  => $m('Membangun Standar Baru', 'Building a New Standard', '树立新标准'),
            'hero_accent' => $m('Industri Karoseri', 'in the Bus Body Industry', '车身制造行业'),
            'hero_lead'   => $m(
                'Dari sebuah bengkel sederhana hingga menjadi raksasa manufaktur otomotif terdepan di Asia Tenggara.',
                'From a humble workshop to a leading automotive manufacturer in Southeast Asia.',
                '从一间简陋的作坊，成长为东南亚领先的汽车制造企业。'
            ),

            'stats' => [
                ['value' => $m(''), 'since' => 1962, 'label' => $m('Tahun Pengalaman', 'Years of Experience', '年经验')],
                ['value' => $m('25 Ha', '25 Ha', '25 公顷'), 'since' => null, 'label' => $m('Area Produksi', 'Production Area', '生产面积')],
                ['value' => $m('1.500+', '1,500+', '1,500+'), 'since' => null, 'label' => $m('Tenaga Ahli', 'Skilled Workforce', '专业员工')],
                ['value' => $m('1.000+', '1,000+', '1,000+'), 'since' => null, 'label' => $m('Unit / Bulan', 'Units / Month', '每月产量（台）')],
            ],

            'director_name'  => 'Bp. David Herman Jaya',
            'director_title' => $m('Presiden Direktur', 'President Director', '总裁'),

            'overview_title' => $m('Perjalanan Panjang Dedikasi & Kualitas', 'A Long Journey of Dedication & Quality', '专注与品质的漫长历程'),
            'overview_body'  => $m(
                "PT Mekar Armada Jaya pertama kali dirintis sebagai bengkel rumahan pada tahun 1962 di Magelang dengan nama Bengkel Las Tiga. Seiring berjalannya waktu dan tingginya perkembangan dunia transportasi, perusahaan bertransformasi besar-besaran hingga resmi menjadi Perseroan Terbatas pada tahun 1981.\n\n"
                ."Didukung dengan luas area produksi sekitar 25 hektar dan lebih dari 1.500 tenaga kerja ahli, PT Mekar Armada Jaya terus berkomitmen menghadirkan kendaraan yang aman, nyaman, dan berkualitas tinggi. Semangat pantang menyerah dan totalitas bekerja mengantarkan perusahaan mampu memproduksi lebih dari 1.000 unit kendaraan per bulan.\n\n"
                .'Sesuai dengan slogan **Pencipta Kendaraan yang Aman dan Nyaman**, perusahaan akan terus memberikan solusi terbaik bagi para mitra kerja dan berkontribusi nyata dalam meningkatkan kualitas hidup masyarakat Indonesia.',

                "PT Mekar Armada Jaya began as a home workshop in 1962 in Magelang under the name Bengkel Las Tiga. As transportation developed rapidly, the company transformed significantly and officially became a limited liability company in 1981.\n\n"
                ."Supported by a production area of around 25 hectares and more than 1,500 skilled employees, PT Mekar Armada Jaya remains committed to delivering safe, comfortable, high-quality vehicles. A tireless spirit and total dedication have enabled the company to produce more than 1,000 vehicles per month.\n\n"
                .'In line with our slogan **Creator of Safe and Comfortable Vehicles**, the company will continue to provide the best solutions for its partners and make a real contribution to improving the quality of life of the Indonesian people.',

                "PT Mekar Armada Jaya 于1962年在马格朗以家庭作坊起家，当时名为 Bengkel Las Tiga。随着交通运输业的快速发展，公司不断转型，并于1981年正式成为有限责任公司。\n\n"
                ."凭借约25公顷的生产区域和1,500多名专业员工，PT Mekar Armada Jaya 始终致力于提供安全、舒适、高品质的车辆。永不放弃的精神与全情投入，使公司每月能够生产1,000多辆车。\n\n"
                .'秉承**安全舒适车辆的创造者**这一理念，公司将继续为合作伙伴提供最佳解决方案，并为提升印尼人民的生活品质作出切实贡献。'
            ),

            'vision_heading'  => $m('Visi Perusahaan', 'Company Vision', '公司愿景'),
            'vision'          => $m(
                'Menjadi Perusahaan *Autobody Manufacturing, Parts, Komponen Otomotif dan Tools* bermutu Berwawasan Internasional.',
                'To become a quality *autobody manufacturing, parts, automotive components and tools* company with an international outlook.',
                '成为具有国际视野的优质*车身制造、零部件、汽车组件及工具*企业。'
            ),
            'mission_heading' => $m('Misi Perusahaan', 'Company Mission', '公司使命'),
            'mission'         => $m(
                "Menciptakan kendaraan yang aman, nyaman, dan berkualitas untuk mencapai tujuan kepuasan pelanggan.\nMemproduksi *parts*, komponen otomotif dan *tool* bermutu tinggi dengan standar *Global Quality*.",
                "Create safe, comfortable, high-quality vehicles to achieve customer satisfaction.\nProduce high-quality *parts*, automotive components and *tools* to *Global Quality* standards.",
                "打造安全、舒适、高品质的车辆，实现客户满意。\n按照*全球质量*标准，生产高品质的*零部件*、汽车组件和*工具*。"
            ),

            'philosophy_title' => $m('Filosofi Perusahaan', 'Company Philosophy', '公司理念'),
            'philosophy_sub'   => $m(
                'Prinsip dasar yang mengakar kuat dalam etos kerja dan budaya korporasi.',
                'Core principles rooted in our work ethic and corporate culture.',
                '深深扎根于我们工作态度与企业文化的基本原则。'
            ),
            'philosophy' => [
                $m('Kepuasan pelanggan adalah komitmen kami', 'Customer satisfaction is our commitment', '客户满意是我们的承诺'),
                $m('Proaktif, kebersamaan dan komunikasi adalah budaya kami', 'Proactiveness, togetherness and communication are our culture', '积极主动、团结协作与沟通是我们的文化'),
                $m('Kerjasama team adalah kekuatan kami', 'Teamwork is our strength', '团队合作是我们的力量'),
                $m('Vendor, distributor dan karyawan adalah rekan kami', 'Vendors, distributors and employees are our partners', '供应商、经销商和员工都是我们的伙伴'),
                $m('Semangat untuk berkembang & pengembangan diri adalah kunci sukses kami', 'The drive to grow and develop ourselves is the key to our success', '不断成长与自我提升的精神是我们成功的关键'),
            ],

            'history_title' => $m('Sejarah & Perjalanan', 'History & Journey', '历史与历程'),
            'history_sub'   => $m(
                'Rekam jejak pertumbuhan PT Mekar Armada Jaya dari masa ke masa.',
                'The growth track record of PT Mekar Armada Jaya over the years.',
                'PT Mekar Armada Jaya 多年来的发展足迹。'
            ),
            'history' => [
                ['from' => 1962, 'to' => null, 'text' => $m(
                    'Berdiri sebagai bengkel rumahan bernama **Bengkel Las Tiga** di Jl. Pawirokusumo No. 3 Magelang, awalnya berfokus memproduksi peralatan rumah tangga.',
                    'Founded as a home workshop called **Bengkel Las Tiga** at Jl. Pawirokusumo No. 3, Magelang, initially focused on producing household equipment.',
                    '以家庭作坊 **Bengkel Las Tiga** 起家，地址位于马格朗 Jl. Pawirokusumo No. 3，最初专注于生产家用器具。'
                )],
                ['from' => 1974, 'to' => null, 'text' => $m(
                    'Merespons perkembangan pesat kendaraan tipe ringan (Colt, Hi-ace), perusahaan mulai mencoba modifikasi mobil menjadi *pick up*, wagon, dan minibus, serta mulai menggunakan nama **Armada**.',
                    'Responding to the rapid growth of light vehicles (Colt, Hi-ace), the company began modifying cars into *pick-ups*, wagons and minibuses, and started using the name **Armada**.',
                    '为应对轻型车辆（Colt、Hi-ace）的快速发展，公司开始将汽车改装为*皮卡*、旅行车和小巴，并开始使用 **Armada** 这一名称。'
                )],
                ['from' => 1978, 'to' => 1981, 'text' => $m(
                    'Seluruh kegiatan dipusatkan pada industri karoseri. Pada **27 April 1981**, resmi berubah status menjadi Perseroan Terbatas dengan nama **PT. Mekar Armada Jaya**.',
                    'All activities were focused on the bus body industry. On **27 April 1981**, the company officially became a limited liability company under the name **PT. Mekar Armada Jaya**.',
                    '所有业务集中于车身制造行业。**1981年4月27日**，公司正式改制为有限责任公司，名称为 **PT. Mekar Armada Jaya**。'
                )],
                ['from' => 1986, 'to' => 1988, 'text' => $m(
                    'Output produksi per bulan mencapai 600 unit dengan ekspansi produk ke bus besar dan boks (Suzuki, Daihatsu, Mitsubishi, Nissan). Memulai ekspor produk ke negara tetangga seperti Srilangka, Brunei, Malaysia, Vietnam, dan Thailand.',
                    'Monthly output reached 600 units with product expansion into large buses and box bodies (Suzuki, Daihatsu, Mitsubishi, Nissan). Exports began to neighbouring countries such as Sri Lanka, Brunei, Malaysia, Vietnam and Thailand.',
                    '月产量达到600台，产品扩展至大型客车和厢式车（铃木、大发、三菱、日产）。开始向斯里兰卡、文莱、马来西亚、越南和泰国等邻国出口产品。'
                )],
                ['from' => 1991, 'to' => null, 'text' => $m(
                    'Pabrik diperluas besar-besaran dan dibentuk Gugus Kendali Mutu (GKM/PMT). Divisi Teknik didirikan untuk menyiapkan komponen *press* (*die*) dengan mesin press berkapasitas 63 ton hingga 2000 ton.',
                    'The factory was expanded significantly and a Quality Control Circle (GKM/PMT) was formed. The Engineering Division was established to prepare *press* components (*dies*) with press machines ranging from 63 to 2,000 tons.',
                    '工厂大规模扩建，并成立品质管理小组（GKM/PMT）。设立技术部门，利用63吨至2000吨的冲压机准备*冲压*部件（*模具*）。'
                )],
                ['from' => 1996, 'to' => 2002, 'text' => $m(
                    'Divisi Teknik berkembang menjadi **Stamping & Workshop Plant**, menginstal mesin CNC Milling dan fasilitas CAD-CAM, serta resmi berganti nama menjadi **Divisi Stamping Tool** yang melayani order nasional (KHS, ADM, TMMIN, ISI, MKM).',
                    'The Engineering Division grew into the **Stamping & Workshop Plant**, installed CNC milling machines and CAD-CAM facilities, and was officially renamed the **Stamping Tool Division**, serving national orders (KHS, ADM, TMMIN, ISI, MKM).',
                    '技术部门发展为**冲压与车间工厂**，安装了数控铣床和CAD-CAM设备，并正式更名为**冲压模具部门**，承接全国订单（KHS、ADM、TMMIN、ISI、MKM）。'
                )],
                ['from' => 2008, 'to' => 2009, 'text' => $m(
                    'Mendapat kepercayaan penuh dari Pemerintah Republik Indonesia untuk mengerjakan proyek-proyek strategis nasional, mulai dari mobil operasional kedinasan, bus kepresidenan, hingga proyek konversi energi pembuatan tabung gas 3 Kg.',
                    'Received the full trust of the Government of the Republic of Indonesia to carry out national strategic projects, from official operational vehicles and presidential buses to the energy conversion project producing 3 Kg gas cylinders.',
                    '获得印度尼西亚共和国政府的充分信任，承担国家战略项目，从公务用车、总统专用客车到生产3公斤燃气罐的能源转换项目。'
                )],
            ],
        ];
    }
}
