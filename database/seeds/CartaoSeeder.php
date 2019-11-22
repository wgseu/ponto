<?php

use App\Models\Cartao;
use App\Models\Forma;
use Illuminate\Database\Seeder;

class CartaoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $credito = Forma::where('descricao', __('messages.credit'))->first();
        $debito = Forma::where('descricao', __('messages.debit'))->first();
        $vale = Forma::where('descricao', __('messages.vale'))->first();

        (new Cartao([
            'forma_id' => $credito->id,
            'bandeira' => "Visa",
            'imagem_url' => "visa.png",
        ]))->save();

        (new Cartao([
            'forma_id' => $credito->id,
            'bandeira' => "MasterCard",
            'imagem_url' => "mastercard.png",
        ]))->save();

        (new Cartao([
            'forma_id' => $credito->id,
            'bandeira' => "Hipercard",
            'imagem_url' => "hipercard.png",
        ]))->save();

        (new Cartao([
            'forma_id' => $credito->id,
            'bandeira' => "Elo",
            'imagem_url' => "elo.png",
        ]))->save();

        (new Cartao([
            'forma_id' => $credito->id,
            'bandeira' => "American Express",
            'imagem_url' => "american_express.png",
        ]))->save();

        (new Cartao([
            'forma_id' => $credito->id,
            'bandeira' => "Diners Club",
            'imagem_url' => "diners_club.png",
        ]))->save();

        (new Cartao([
            'forma_id' => $debito->id,
            'bandeira' => "Maestro",
            'imagem_url' => "maestro.png",
        ]))->save();

        (new Cartao([
            'forma_id' => $debito->id,
            'bandeira' => "Visa Electron",
            'imagem_url' => "visa_electron.png",
        ]))->save();

        (new Cartao([
            'forma_id' => $debito->id,
            'bandeira' => "Elo",
            'imagem_url' => "elo.png",
        ]))->save();

        (new Cartao([
            'forma_id' => $vale->id,
            'bandeira' => "Sodexo",
            'imagem_url' => "sodexo.png",
        ]))->save();

        (new Cartao([
            'forma_id' => $vale->id,
            'bandeira' => "Ticket",
            'imagem_url' => "ticket.png",
        ]))->save();
    }
}
