<?php

namespace Database\Seeders;

use App\Models\MediaCategory;
use App\Models\Page;
use App\Models\Section;
use App\Models\Service;
use App\Models\Setting;
use Illuminate\Database\Seeder;

class InitialContentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $home = Page::query()->updateOrCreate(
            ['slug' => 'home'],
            [
                'title' => 'Home',
                'hero_title' => 'Soluções Inteligentes para Infraestrutura Hídrica',
                'hero_subtitle' => 'Especialistas em irrigação e pivôs, saneamento e monitoramento hidrológico em tempo real.',
                'content' => 'A Felestrino Soluções desenvolve projetos completos para eficiência operacional e segurança hídrica.',
                'meta_title' => 'Felestrino Soluções',
                'meta_description' => 'Soluções para irrigação, pivôs, água, esgoto e monitoramento de chuvas e rios.',
                'is_published' => true,
                'sort_order' => 1,
            ]
        );

        Section::query()->updateOrCreate(
            ['page_id' => $home->id, 'title' => 'Destaque de Infraestrutura'],
            [
                'type' => 'highlight',
                'content' => 'Integração entre automação, sensores e gestão de dados para tomada de decisão com precisão.',
                'sort_order' => 1,
                'is_active' => true,
            ]
        );

        Page::query()->updateOrCreate(
            ['slug' => 'sobre'],
            [
                'title' => 'Sobre',
                'hero_title' => 'Tecnologia aplicada ao ciclo da água',
                'hero_subtitle' => 'Atuamos com engenharia, monitoramento e suporte especializado.',
                'content' => 'Somos uma empresa focada em performance hídrica, previsibilidade e controle operacional.',
                'meta_title' => 'Sobre a Felestrino Soluções',
                'meta_description' => 'Conheça a empresa e sua atuação em infraestrutura hídrica inteligente.',
                'is_published' => true,
                'sort_order' => 2,
            ]
        );

        Service::query()->updateOrCreate(
            ['slug' => 'solucoes-inteligentes-para-irrigacao-e-pivos'],
            [
                'title' => 'Soluções Inteligentes para Irrigação e Pivôs',
                'excerpt' => 'Automação e controle preciso para reduzir desperdícios e elevar produtividade.',
                'content' => 'Projetos de modernização de pivôs, telemetria e dashboards operacionais para irrigação.',
                'icon' => 'droplet',
                'sort_order' => 1,
                'is_active' => true,
            ]
        );

        Service::query()->updateOrCreate(
            ['slug' => 'controle-e-monitoramento-de-sistemas-de-agua-e-esgoto'],
            [
                'title' => 'Controle e Monitoramento de Sistemas de Água e Esgoto',
                'excerpt' => 'Supervisão contínua para eficiência e conformidade operacional.',
                'content' => 'Monitoramento de pressão, vazão e nível com alertas e histórico de indicadores.',
                'icon' => 'gauge',
                'sort_order' => 2,
                'is_active' => true,
            ]
        );

        Service::query()->updateOrCreate(
            ['slug' => 'monitoramento-de-chuvas-e-niveis-de-rios-em-tempo-real'],
            [
                'title' => 'Monitoramento de Chuvas e Níveis de Rios em Tempo Real',
                'excerpt' => 'Dados hidrológicos em tempo real para prevenção e resposta rápida.',
                'content' => 'Estações de monitoramento conectadas com visualização online e alertas automáticos.',
                'icon' => 'cloud-rain',
                'sort_order' => 3,
                'is_active' => true,
            ]
        );

        MediaCategory::query()->updateOrCreate(
            ['slug' => 'projetos'],
            ['name' => 'Projetos', 'sort_order' => 1]
        );

        MediaCategory::query()->updateOrCreate(
            ['slug' => 'institucional'],
            ['name' => 'Institucional', 'sort_order' => 2]
        );

        $settings = [
            'company_name' => 'Felestrino Soluções',
            'company_email' => 'contato@felestrino.com.br',
            'company_phone' => '(11) 99999-9999',
            'company_whatsapp' => '(11) 99999-9999',
            'company_address' => 'São Paulo - SP',
            'about_summary' => 'Especialistas em soluções inteligentes para gestão de recursos hídricos.',
            'hero_video_url' => '',
            'seo_default_title' => 'Felestrino Soluções',
            'seo_default_description' => 'Empresa especializada em irrigação, saneamento e monitoramento hidrológico.',
        ];

        foreach ($settings as $key => $value) {
            Setting::query()->updateOrCreate(['key' => $key], ['value' => $value]);
        }
    }
}
