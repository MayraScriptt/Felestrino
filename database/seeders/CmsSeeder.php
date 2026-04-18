<?php

namespace Database\Seeders;

use App\Models\Page;
use App\Models\Section;
use App\Models\Service;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class CmsSeeder extends Seeder
{
    public function run(): void
    {
        User::query()->updateOrCreate(
            ['email' => 'admin@felestrino.com.br'],
            [
                'name' => 'Administrador',
                'password' => Hash::make('admin1234'),
                'is_admin' => true,
            ]
        );

        $settings = [
            'company_name' => 'Felestrino Solucoes',
            'tagline' => 'Tecnologia para monitoramento hidrico e irrigacao inteligente',
            'phone' => '(11) 99999-9999',
            'email' => 'contato@felestrino.com.br',
            'address' => 'Sao Paulo - SP',
            'about' => 'Especialistas em automacao, telemetria e inteligencia operacional para irrigacao, agua e esgoto.',
            'hero_title' => 'Solucoes inteligentes para irrigacao e pivos',
            'hero_subtitle' => 'Controle e monitoramento de sistemas hidricos em tempo real.',
            'hero_image_url' => '/images/hero.jpg',
            'seo_title' => 'Felestrino Solucoes | Irrigacao e Monitoramento Hidrico',
            'seo_description' => 'Controle de irrigacao, agua e esgoto com telemetria e monitoramento de chuvas e niveis de rios.',
        ];

        foreach ($settings as $key => $value) {
            Setting::query()->updateOrCreate(['key' => $key], ['value' => $value]);
        }

        $home = Page::query()->updateOrCreate(
            ['slug' => 'home'],
            [
                'title' => 'Home',
                'meta_title' => 'Felestrino Solucoes',
                'meta_description' => 'Solucoes em irrigacao, saneamento e monitoramento hidrologico.',
                'content' => 'Empresa especializada em solucoes tecnicas para a gestao eficiente de recursos hidricos.',
                'is_published' => true,
                'display_order' => 0,
            ]
        );

        $about = Page::query()->updateOrCreate(
            ['slug' => 'sobre'],
            [
                'title' => 'Sobre a Empresa',
                'meta_title' => 'Sobre | Felestrino Solucoes',
                'meta_description' => 'Conheca nossa historia e experiencia em automacao hidrica.',
                'content' => 'Atuamos em projetos de alta confiabilidade com foco em performance e seguranca operacional.',
                'is_published' => true,
                'display_order' => 1,
            ]
        );

        $sections = [
            [
                'page_id' => $home->id,
                'title' => 'Solucoes Inteligentes para Irrigacao e Pivos',
                'section_key' => 'irrigacao',
                'subtitle' => 'Automacao para produtividade e economia de agua',
                'content' => 'Projetos de controle de pivos centrais, sensores de solo e acionamentos remotos para irrigacao precisa.',
                'display_order' => 1,
            ],
            [
                'page_id' => $home->id,
                'title' => 'Controle e Monitoramento de Sistemas de Agua e Esgoto',
                'section_key' => 'saneamento',
                'subtitle' => 'Supervisao operacional para redes criticas',
                'content' => 'Telemetria para estacoes elevatorias, reservatorios e redes de distribuicao com alertas em tempo real.',
                'display_order' => 2,
            ],
            [
                'page_id' => $home->id,
                'title' => 'Monitoramento de Chuvas e Niveis de Rios em Tempo Real',
                'section_key' => 'hidrologia',
                'subtitle' => 'Dados confiaveis para tomada de decisao',
                'content' => 'Estacoes hidrologicas conectadas para analise de risco, prevencao de eventos extremos e apoio a defesa civil.',
                'display_order' => 3,
            ],
            [
                'page_id' => $about->id,
                'title' => 'Nosso Compromisso',
                'section_key' => 'compromisso',
                'subtitle' => 'Inovacao com resultado',
                'content' => 'Entregamos solucoes completas: levantamento, projeto, implantacao e suporte tecnico especializado.',
                'display_order' => 1,
            ],
        ];

        foreach ($sections as $section) {
            Section::query()->updateOrCreate(
                ['page_id' => $section['page_id'], 'section_key' => $section['section_key']],
                $section + ['is_published' => true]
            );
        }

        $services = [
            [
                'title' => 'Automacao de Irrigacao e Pivos',
                'slug' => 'automacao-irrigacao-pivos',
                'short_description' => 'Controle inteligente com sensores e atuadores.',
                'description' => 'Integracao de CLPs, telemetria e dashboards para maximizar eficiencia hidrica.',
                'is_highlight' => true,
                'display_order' => 1,
            ],
            [
                'title' => 'Supervisao de Agua e Esgoto',
                'slug' => 'supervisao-agua-esgoto',
                'short_description' => 'Monitoramento operacional de redes e ativos.',
                'description' => 'Painel com alarmes, historico e indicadores para operacao segura e continua.',
                'is_highlight' => true,
                'display_order' => 2,
            ],
            [
                'title' => 'Monitoramento Hidrologico',
                'slug' => 'monitoramento-hidrologico',
                'short_description' => 'Chuvas e niveis de rios em tempo real.',
                'description' => 'Coleta automatica de dados de campo para gestao de risco e planejamento.',
                'is_highlight' => true,
                'display_order' => 3,
            ],
        ];

        foreach ($services as $service) {
            Service::query()->updateOrCreate(
                ['slug' => $service['slug']],
                $service + ['is_published' => true]
            );
        }
    }
}
