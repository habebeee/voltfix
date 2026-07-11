<?php

namespace App\Filament\Admin\Pages;

use App\Models\SiteSetting;
use Filament\Forms;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;

class ManageHomePage extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-photo';

    protected static ?string $navigationGroup = 'Konten Website';

    protected static ?string $navigationLabel = 'Halaman Utama';

    protected static ?string $title = 'Kelola Halaman Utama';

    protected static ?int $navigationSort = 1;

    protected static string $view = 'filament.admin.pages.manage-home-page';

    public ?array $data = [];

    public static function canAccess(): bool
    {
        return in_array(auth()->user()?->role, ['ADMIN', 'MANAGER']);
    }

    public function mount(): void
    {
        $settings = SiteSetting::getHomeSettings();

        $this->form->fill([
            'site_name'                => $settings['site_name'] ?? 'Voltfix',
            'logo'                     => $settings['logo'],
            'hero_image'               => $settings['hero_image'],
            'step_1_image'             => $settings['step_1_image'],
            'step_2_image'             => $settings['step_2_image'],
            'step_3_image'             => $settings['step_3_image'],
            'service_tv_image'         => $settings['service_tv_image'],
            'service_hp_image'         => $settings['service_hp_image'],
            'service_laptop_image'     => $settings['service_laptop_image'],
            'cta_background_image'     => $settings['cta_background_image'],
        ]);
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Logo & Brand')
                    ->description('Logo ditampilkan di navbar dan footer halaman utama.')
                    ->schema([
                        Forms\Components\TextInput::make('site_name')
                            ->label('Nama Brand')
                            ->required()
                            ->maxLength(100)
                            ->default('Voltfix'),
                        Forms\Components\FileUpload::make('logo')
                            ->label('Logo')
                            ->image()
                            ->disk('public')
                            ->directory('site')
                            ->visibility('public')
                            ->maxSize(5120)
                            ->imageEditor()
                            ->imagePreviewHeight('120')
                            ->helperText('Rekomendasi: PNG transparan, ukuran 200×200 px. Maks 5MB.'),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Hero Section')
                    ->description('Gambar utama di bagian atas halaman.')
                    ->schema([
                        Forms\Components\FileUpload::make('hero_image')
                            ->label('Gambar Hero')
                            ->image()
                            ->disk('public')
                            ->directory('site')
                            ->visibility('public')
                            ->maxSize(10240)
                            ->imageEditor()
                            ->imagePreviewHeight('200')
                            ->helperText('Rekomendasi: landscape 1200×800 px. Maks 10MB.'),
                    ]),

                Forms\Components\Section::make('Cara Kerja — Foto Langkah')
                    ->description('Foto untuk 3 langkah cara kerja Voltfix.')
                    ->schema([
                        Forms\Components\FileUpload::make('step_1_image')
                            ->label('Langkah 1 — Ajukan Tiket')
                            ->image()
                            ->disk('public')
                            ->directory('site')
                            ->visibility('public')
                            ->maxSize(5120)
                            ->imageEditor()
                            ->imagePreviewHeight('150'),
                        Forms\Components\FileUpload::make('step_2_image')
                            ->label('Langkah 2 — Teknisi Datang')
                            ->image()
                            ->disk('public')
                            ->directory('site')
                            ->visibility('public')
                            ->maxSize(5120)
                            ->imageEditor()
                            ->imagePreviewHeight('150'),
                        Forms\Components\FileUpload::make('step_3_image')
                            ->label('Langkah 3 — Selesai & Rating')
                            ->image()
                            ->disk('public')
                            ->directory('site')
                            ->visibility('public')
                            ->maxSize(5120)
                            ->imageEditor()
                            ->imagePreviewHeight('150'),
                    ])
                    ->columns(3),

                Forms\Components\Section::make('Layanan Servis — Foto')
                    ->description('Foto untuk kartu layanan TV/Monitor, HP, dan Laptop.')
                    ->schema([
                        Forms\Components\FileUpload::make('service_tv_image')
                            ->label('TV & Monitor')
                            ->image()
                            ->disk('public')
                            ->directory('site')
                            ->visibility('public')
                            ->maxSize(5120)
                            ->imageEditor()
                            ->imagePreviewHeight('150'),
                        Forms\Components\FileUpload::make('service_hp_image')
                            ->label('HP / Smartphone')
                            ->image()
                            ->disk('public')
                            ->directory('site')
                            ->visibility('public')
                            ->maxSize(5120)
                            ->imageEditor()
                            ->imagePreviewHeight('150'),
                        Forms\Components\FileUpload::make('service_laptop_image')
                            ->label('Laptop')
                            ->image()
                            ->disk('public')
                            ->directory('site')
                            ->visibility('public')
                            ->maxSize(5120)
                            ->imageEditor()
                            ->imagePreviewHeight('150'),
                    ])
                    ->columns(3),

                Forms\Components\Section::make('CTA Banner')
                    ->description('Gambar latar belakang banner ajukan daftar.')
                    ->schema([
                        Forms\Components\FileUpload::make('cta_background_image')
                            ->label('Background CTA')
                            ->image()
                            ->disk('public')
                            ->directory('site')
                            ->visibility('public')
                            ->maxSize(10240)
                            ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp', 'image/gif'])
                            ->imageEditor()
                            ->imagePreviewHeight('200')
                            ->helperText('Maks 10MB · JPG/PNG/WEBP. Opsional — jika kosong, gradient default dipakai.'),
                    ]),
            ])
            ->statePath('data');
    }

    public function save(): void
    {
        $data = $this->form->getState();

        $imageKeys = [
            'logo',
            'hero_image',
            'step_1_image',
            'step_2_image',
            'step_3_image',
            'service_tv_image',
            'service_hp_image',
            'service_laptop_image',
            'cta_background_image',
        ];

        SiteSetting::set('site_name', $data['site_name'] ?? 'Voltfix');

        foreach ($imageKeys as $key) {
            $value = $data[$key] ?? null;

            if (is_array($value)) {
                $value = $value[0] ?? null;
            }

            SiteSetting::set($key, $value ?: null, 'image');
        }

        Notification::make()
            ->title('Halaman utama berhasil disimpan')
            ->success()
            ->send();
    }
}
