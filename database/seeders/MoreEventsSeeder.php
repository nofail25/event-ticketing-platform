<?php

namespace Database\Seeders;

use App\Models\Event;
use App\Models\TicketCategory;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class MoreEventsSeeder extends Seeder
{
    public function run(): void
    {
        $organizer = User::where('email', 'organizer@ticketing.com')->firstOrFail();

        $eventsData = [
            [
                'title'       => 'Konser Dewa 19 Reunion',
                'description' => 'Malam penuh nostalgia bersama legenda musik Indonesia, Dewa 19. Nikmati lagu-lagu hits sepanjang masa yang telah menemani perjalanan hidup Anda.',
                'location'    => 'Stadion Gelora Bung Karno, Jakarta',
                'start_time'  => '2026-08-14 19:00:00',
                'end_time'    => '2026-08-14 23:00:00',
                'status'      => 'active',
                'categories'  => [
                    ['name' => 'VVIP', 'price' => 1500000, 'quota' => 50],
                    ['name' => 'VIP', 'price' => 850000, 'quota' => 200],
                    ['name' => 'Regular', 'price' => 350000, 'quota' => 2000],
                ],
                'picsum_seed' => 'concert1',
            ],
            [
                'title'       => 'DevFest Indonesia 2026',
                'description' => 'Konferensi developer terbesar di Indonesia. Pelajari teknologi terbaru Google, AI/ML, web dan mobile development dari para pakar industri.',
                'location'    => 'Balai Kartini, Jakarta',
                'start_time'  => '2026-09-05 08:00:00',
                'end_time'    => '2026-09-06 17:00:00',
                'status'      => 'active',
                'categories'  => [
                    ['name' => 'Early Bird', 'price' => 200000, 'quota' => 100],
                    ['name' => 'Regular', 'price' => 350000, 'quota' => 500],
                    ['name' => 'Workshop Pass', 'price' => 600000, 'quota' => 150],
                ],
                'picsum_seed' => 'tech2026',
            ],
            [
                'title'       => 'Bali Spirit Festival',
                'description' => 'Festival yoga, musik, dan seni yang menginspirasi jiwa. Bergabunglah dengan ribuan peserta dari seluruh dunia di pulau dewata.',
                'location'    => 'Ubud, Bali',
                'start_time'  => '2026-10-10 07:00:00',
                'end_time'    => '2026-10-14 22:00:00',
                'status'      => 'active',
                'categories'  => [
                    ['name' => 'Full Pass', 'price' => 2500000, 'quota' => 300],
                    ['name' => 'Day Pass', 'price' => 650000, 'quota' => 800],
                ],
                'picsum_seed' => 'bali_spirit',
            ],
            [
                'title'       => 'Indonesia Comic Con 2026',
                'description' => 'Perhelatan pop culture terbesar di Asia Tenggara! Cosplay, gaming, anime, film, dan bertemu langsung bintang idola Anda.',
                'location'    => 'Jakarta Convention Center, Jakarta',
                'start_time'  => '2026-10-24 10:00:00',
                'end_time'    => '2026-10-25 21:00:00',
                'status'      => 'active',
                'categories'  => [
                    ['name' => 'VIP 2 Hari', 'price' => 750000, 'quota' => 200],
                    ['name' => 'Regular 2 Hari', 'price' => 400000, 'quota' => 1000],
                    ['name' => 'Regular 1 Hari', 'price' => 250000, 'quota' => 2000],
                ],
                'picsum_seed' => 'comiccon2026',
            ],
            [
                'title'       => 'Workshop UI/UX Design Modern',
                'description' => 'Pelajari prinsip desain UI/UX terkini, prototyping dengan Figma, dan cara menciptakan pengalaman pengguna yang luar biasa bersama desainer senior.',
                'location'    => 'GDP Venture, Jakarta Selatan',
                'start_time'  => '2026-07-25 09:00:00',
                'end_time'    => '2026-07-25 17:00:00',
                'status'      => 'active',
                'categories'  => [
                    ['name' => 'Seat Only', 'price' => 450000, 'quota' => 50],
                ],
                'picsum_seed' => 'uxworkshop',
            ],
            [
                'title'       => 'Surabaya Jazz Festival',
                'description' => 'Malam jazz yang memukau di kota pahlawan. Nikmati penampilan musisi jazz lokal dan internasional dalam suasana yang elegan dan nyaman.',
                'location'    => 'Tunjungan Plaza, Surabaya',
                'start_time'  => '2026-08-29 17:00:00',
                'end_time'    => '2026-08-29 23:00:00',
                'status'      => 'active',
                'categories'  => [
                    ['name' => 'Premium Table', 'price' => 1200000, 'quota' => 60],
                    ['name' => 'VIP Standing', 'price' => 500000, 'quota' => 300],
                    ['name' => 'General', 'price' => 200000, 'quota' => 1000],
                ],
                'picsum_seed' => 'jazz_surabaya',
            ],
            [
                'title'       => 'Lari Pagi Nusantara 5K & 10K',
                'description' => 'Fun run untuk semua kalangan! Nikmati pagi hari yang sehat sambil menjelajahi keindahan kota Bandung. Tersedia kategori 5K dan 10K.',
                'location'    => 'Lapangan Gasibu, Bandung',
                'start_time'  => '2026-07-19 05:30:00',
                'end_time'    => '2026-07-19 10:00:00',
                'status'      => 'active',
                'categories'  => [
                    ['name' => '5K', 'price' => 150000, 'quota' => 1000],
                    ['name' => '10K', 'price' => 250000, 'quota' => 500],
                ],
                'picsum_seed' => 'funrun_bandung',
            ],
            [
                'title'       => 'Seminar Kewirausahaan Muda',
                'description' => 'Inspirasi dan edukasi bagi generasi muda yang ingin memulai bisnis. Hadiri sesi dari pengusaha sukses dan investor terkemuka Indonesia.',
                'location'    => 'Trans Studio Mall, Bandung',
                'start_time'  => '2026-09-20 09:00:00',
                'end_time'    => '2026-09-20 16:00:00',
                'status'      => 'active',
                'categories'  => [
                    ['name' => 'Student', 'price' => 75000, 'quota' => 200],
                    ['name' => 'Umum', 'price' => 150000, 'quota' => 300],
                ],
                'picsum_seed' => 'entrepreneur_seminar',
            ],
            [
                'title'       => 'Pameran Foto & Seni Kontemporer',
                'description' => 'Jelajahi karya seni fotografi terbaik dari seniman Indonesia dan mancanegara. Pameran interaktif dengan workshop foto gratis setiap hari.',
                'location'    => 'Galeri Nasional Indonesia, Jakarta Pusat',
                'start_time'  => '2026-11-01 10:00:00',
                'end_time'    => '2026-11-07 20:00:00',
                'status'      => 'active',
                'categories'  => [
                    ['name' => 'Tiket Masuk', 'price' => 50000, 'quota' => 5000],
                    ['name' => 'Workshop Pass', 'price' => 250000, 'quota' => 100],
                ],
                'picsum_seed' => 'art_exhibition',
            ],
            [
                'title'       => 'Yogyakarta Cultural Night',
                'description' => 'Sebuah pertunjukan malam memukau yang menampilkan tari tradisional Jawa, wayang kulit, dan gamelan di latar belakang Candi Prambanan yang megah.',
                'location'    => 'Kompleks Candi Prambanan, Yogyakarta',
                'start_time'  => '2026-12-20 18:30:00',
                'end_time'    => '2026-12-20 22:00:00',
                'status'      => 'active',
                'categories'  => [
                    ['name' => 'VVIP Tribun', 'price' => 1000000, 'quota' => 100],
                    ['name' => 'VIP', 'price' => 600000, 'quota' => 300],
                    ['name' => 'Regular', 'price' => 250000, 'quota' => 800],
                ],
                'picsum_seed' => 'yogyakarta_night',
            ],
            [
                'title'       => 'Blockchain & Web3 Indonesia Summit',
                'description' => 'Pertemuan para pionir blockchain, crypto, dan Web3 terbesar di Asia Tenggara. Panel diskusi, demo proyek, dan networking eksklusif.',
                'location'    => 'Hotel Mulia Senayan, Jakarta',
                'start_time'  => '2026-10-15 08:30:00',
                'end_time'    => '2026-10-16 18:00:00',
                'status'      => 'active',
                'categories'  => [
                    ['name' => 'VIP All Access', 'price' => 2000000, 'quota' => 80],
                    ['name' => 'Standard', 'price' => 800000, 'quota' => 400],
                ],
                'picsum_seed' => 'blockchain_summit',
            ],
            [
                'title'       => 'Culinary Masters Bandung',
                'description' => 'Festival kuliner premium yang menampilkan chef bintang dari seluruh Indonesia. Cooking show langsung, food tasting, dan kelas memasak eksklusif.',
                'location'    => 'Braga CityWalk, Bandung',
                'start_time'  => '2026-11-28 11:00:00',
                'end_time'    => '2026-11-29 21:00:00',
                'status'      => 'active',
                'categories'  => [
                    ['name' => 'Chef Table', 'price' => 750000, 'quota' => 40],
                    ['name' => 'Festival Pass', 'price' => 200000, 'quota' => 2000],
                    ['name' => 'Gratis', 'price' => 0, 'quota' => 500],
                ],
                'picsum_seed' => 'culinary_bandung',
            ],
        ];

        $imageSeeds = [
            'concert1'           => '1090',
            'tech2026'           => '180',
            'bali_spirit'        => '1018',
            'comiccon2026'       => '1074',
            'uxworkshop'         => '430',
            'jazz_surabaya'      => '374',
            'funrun_bandung'     => '390',
            'entrepreneur_seminar' => '239',
            'art_exhibition'     => '305',
            'yogyakarta_night'   => '1041',
            'blockchain_summit'  => '1035',
            'culinary_bandung'   => '292',
        ];

        foreach ($eventsData as $eventData) {
            $categories = $eventData['categories'];
            $picsumSeed = $eventData['picsum_seed'];
            unset($eventData['categories'], $eventData['picsum_seed']);

            // Download and save banner image
            $bannerPath = null;
            $picsumId = $imageSeeds[$picsumSeed] ?? '100';
            $imageUrl = "https://picsum.photos/id/{$picsumId}/1200/675";

            try {
                $response = Http::timeout(15)->get($imageUrl);
                if ($response->successful()) {
                    $filename = 'banners/' . $picsumSeed . '.jpg';
                    Storage::disk('public')->put($filename, $response->body());
                    $bannerPath = $filename;
                    $this->command->info("Downloaded banner for: {$eventData['title']}");
                }
            } catch (\Exception $e) {
                $this->command->warn("Failed to download banner for {$eventData['title']}: " . $e->getMessage());
            }

            $event = Event::firstOrCreate(
                ['title' => $eventData['title'], 'organizer_id' => $organizer->id],
                array_merge($eventData, [
                    'organizer_id' => $organizer->id,
                    'banner_image' => $bannerPath,
                ])
            );

            // Update banner if event already exists but no image
            if ($event->wasRecentlyCreated === false && !$event->banner_image && $bannerPath) {
                $event->update(['banner_image' => $bannerPath]);
            }

            foreach ($categories as $cat) {
                TicketCategory::firstOrCreate(
                    ['event_id' => $event->id, 'name' => $cat['name']],
                    [
                        'event_id' => $event->id,
                        'name'     => $cat['name'],
                        'price'    => $cat['price'],
                        'quota'    => $cat['quota'],
                    ]
                );
            }
        }

        $this->command->info('✅ 12 event baru berhasil ditambahkan beserta gambar!');
    }
}
