<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProdutividadeSeeder extends Seeder
{
    public function run()
    {
        $linhas = [
            'geladeira',
            'maquina',
            'tv',
            'arcondicionado',
        ];

        $inicio = new DateTime('2026-01-01');
        $fim = new DateTime('2026-02-01');

        $rows = [];

        for ($d = clone $inicio; $d < $fim; $d->modify('+1 day')) {
            foreach ($linhas as $linha) {
                $produzida = random_int(220, 520);
                $defeitos = random_int(0, (int) floor($produzida * 0.12));

                $rows[] = [
                    'linha' => $linha,
                    'data_producao' => $d->format('Y-m-d'),
                    'quantidade_produzida' => $produzida,
                    'quantidade_defeitos' => $defeitos,
                ];
            }
        }

        DB::table('produtividades')->truncate();
        DB::table('produtividades')->insert($rows);
    }
}
