<?php

use App\Util\Upload;
use App\Models\Forma;
use App\Models\Cartao;
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
            'imagem' => Upload::getResource('images/cards/visa.png'),
        ]))->save();

        (new Cartao([
            'forma_id' => $credito->id,
            'bandeira' => "MasterCard",
            'imagem' => Upload::getResource('images/cards/mastercard.png'),
        ]))->save();

        (new Cartao([
            'forma_id' => $credito->id,
            'bandeira' => "Hipercard",
            'imagem' => Upload::getResource('images/cards/hipercard.png'),
        ]))->save();

        (new Cartao([
            'forma_id' => $credito->id,
            'bandeira' => "Elo",
            'imagem' => Upload::getResource('images/cards/elo.png'),
        ]))->save();

        (new Cartao([
            'forma_id' => $credito->id,
            'bandeira' => "American Express",
            'imagem' => Upload::getResource('images/cards/american_express.png'),
        ]))->save();

        (new Cartao([
            'forma_id' => $credito->id,
            'bandeira' => "Diners Club",
            'imagem' => Upload::getResource('images/cards/diners_club.png'),
        ]))->save();

        (new Cartao([
            'forma_id' => $debito->id,
            'bandeira' => "Maestro",
            'imagem' => Upload::getResource('images/cards/maestro.png'),
        ]))->save();

        (new Cartao([
            'forma_id' => $debito->id,
            'bandeira' => "Visa Electron",
            'imagem' => Upload::getResource('images/cards/visa_electron.png'),
        ]))->save();

        (new Cartao([
            'forma_id' => $debito->id,
            'bandeira' => "Elo",
            'imagem' => Upload::getResource('images/cards/elo.png'),
        ]))->save();

        (new Cartao([
            'forma_id' => $vale->id,
            'bandeira' => "Sodexo",
            'imagem' => Upload::getResource('images/cards/sodexo.png'),
        ]))->save();

        (new Cartao([
            'forma_id' => $vale->id,
            'bandeira' => "Ticket",
            'imagem' => Upload::getResource('images/cards/ticket.png'),
        ]))->save();
    }
}
