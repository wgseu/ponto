<?php

use App\Models\Banco;
use Illuminate\Database\Seeder;

class BancoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        (new Banco([
            'numero' => '1',
            'fantasia' => 'Banco do Brasil',
            'razao_social' => 'Banco do Brasil S.A.',
            'agencia_mascara' => '9999->a',
            'conta_mascara' => '99.999->a',
        ]))->save();

        (new Banco([
            'numero' => '3',
            'fantasia' => 'Banco da Amazônia',
            'razao_social' => 'Banco da Amazônia S.A.',
        ]))->save();

        (new Banco([
            'numero' => '4',
            'fantasia' => 'Banco do Nordeste',
            'razao_social' => 'Banco do Nordeste do Brasil S.A.',
        ]))->save();

        (new Banco([
            'numero' => '7',
            'fantasia' => 'BNDS',
            'razao_social' => 'Banco Nacional de Desenvolvimento Econômico e Social',
        ]))->save();

        (new Banco([
            'numero' => '12',
            'fantasia' => 'Banco INBURSA de Investimentos',
            'razao_social' => 'Banco INBURSA de Investimentos S.A.',
        ]))->save();

        (new Banco([
            'numero' => '14',
            'fantasia' => 'Natixis Brasil',
            'razao_social' => 'Natixis Brasil S.A. Banco Múltiplo',
        ]))->save();

        (new Banco([
            'numero' => '17',
            'fantasia' => 'BNY Mellon',
            'razao_social' => 'BNY Mellon Banco S.A.',
        ]))->save();

        (new Banco([
            'numero' => '18',
            'fantasia' => 'Banco Tricury',
            'razao_social' => 'Banco Tricury S.A.',
        ]))->save();

        (new Banco([
            'numero' => '19',
            'fantasia' => 'Banco Azteca do Brasil',
            'razao_social' => 'Banco Azteca do Brasil S.A.',
        ]))->save();

        (new Banco([
            'numero' => '21',
            'fantasia' => 'BANESTES',
            'razao_social' => 'BANESTES S.A. Banco do Estado do Espírito Santo',
        ]))->save();

        (new Banco([
            'numero' => '24',
            'fantasia' => 'Banco BANDEPE',
            'razao_social' => 'Banco BANDEPE S.A',
        ]))->save();

        (new Banco([
            'numero' => '25',
            'fantasia' => 'Banco Alfa',
            'razao_social' => 'Banco Alfa S.A.',
        ]))->save();

        (new Banco([
            'numero' => '29',
            'fantasia' => 'Itaú Consignado',
            'razao_social' => 'Banco Itaú Consignado S.A.',
        ]))->save();

        (new Banco([
            'numero' => '33',
            'fantasia' => 'Santander',
            'razao_social' => 'Banco Santander (Brasil) S.A.',
            'agencia_mascara' => '9999',
            'conta_mascara' => '9.999.999.999',
        ]))->save();

        (new Banco([
            'numero' => '36',
            'fantasia' => 'Banco Bradesco BBI',
            'razao_social' => 'Banco Bradesco BBI S.A.',
        ]))->save();

        (new Banco([
            'numero' => '37',
            'fantasia' => 'Banco do Estado do Pará',
            'razao_social' => 'Banco do Estado do Pará S.A.',
        ]))->save();

        (new Banco([
            'numero' => '39',
            'fantasia' => 'Banco do Estado do Piauí',
            'razao_social' => 'Banco do Estado do Piauí S.A. - BEP',
        ]))->save();

        (new Banco([
            'numero' => '40',
            'fantasia' => 'Banco Cargill',
            'razao_social' => 'Banco Cargill S.A.',
        ]))->save();

        (new Banco([
            'numero' => '41',
            'fantasia' => 'Banco do Estado do Rio Grande do Sul',
            'razao_social' => 'Banco do Estado do Rio Grande do Sul S.A',
        ]))->save();

        (new Banco([
            'numero' => '44',
            'fantasia' => 'Banco BVA',
            'razao_social' => 'Banco BVA S.A',
        ]))->save();

        (new Banco([
            'numero' => '47',
            'fantasia' => 'Banco do Estado de Sergipe',
            'razao_social' => 'Banco do Estado de Sergipe S.A.',
        ]))->save();
        
        (new Banco([
            'numero' => '62',
            'fantasia' => 'Hipercard',
            'razao_social' => 'Hipercard Banco Múltiplo S.A.',
        ]))->save();

        (new Banco([
            'numero' => '63',
            'fantasia' => 'Bradescard',
            'razao_social' => 'Banco Bradescard S.A.',
        ]))->save();

        (new Banco([
            'numero' => '64',
            'fantasia' => 'Goldman Sachs do Brasil',
            'razao_social' => 'Goldman Sachs do Brasil Banco Múltiplo S.A.',
        ]))->save();

        (new Banco([
            'numero' => '65',
            'fantasia' => 'Banco Andbank (Brasil)',
            'razao_social' => 'Banco Andbank (Brasil) S.A.',
        ]))->save();

        (new Banco([
            'numero' => '66',
            'fantasia' => 'Banco Morgan Stanley',
            'razao_social' => 'Banco Morgan Stanley S.A.',
        ]))->save();

        (new Banco([
            'numero' => '69',
            'fantasia' => 'Crefisa',
            'razao_social' => 'Banco Crefisa S.A.',
        ]))->save();

        (new Banco([
            'numero' => '70',
            'fantasia' => 'BRB - Banco de Brasília',
            'razao_social' => 'BRB - Banco de Brasília S.A.',
        ]))->save();

        (new Banco([
            'numero' => '72',
            'fantasia' => 'Banco Mais',
            'razao_social' => 'Banco Mais S.A',
        ]))->save();

        (new Banco([
            'numero' => '74',
            'fantasia' => 'Banco J. Safra S.A.',
            'razao_social' => 'Banco J. Safra',
        ]))->save();

        (new Banco([
            'numero' => '75',
            'fantasia' => 'Banco ABN AMRO',
            'razao_social' => 'Banco ABN AMRO S.A.',
        ]))->save();

        (new Banco([
            'numero' => '76',
            'fantasia' => 'Banco KDB',
            'razao_social' => 'Banco KDB S.A.',
        ]))->save();

        (new Banco([
            'numero' => '77',
            'fantasia' => 'Banco Inter S.A.',
            'razao_social' => 'Banco Inter S.A.',
        ]))->save();

        (new Banco([
            'numero' => '78',
            'fantasia' => 'Haitong Banco de Investimento do Brasil',
            'razao_social' => 'Haitong Banco de Investimento do Brasil S.A.',
        ]))->save();

        (new Banco([
            'numero' => '79',
            'fantasia' => 'Banco Original do Agronegócio',
            'razao_social' => 'Banco Original do Agronegócio S.A.',
        ]))->save();

        (new Banco([
            'numero' => '81',
            'fantasia' => 'BBN Banco Brasileiro de Negócios',
            'razao_social' => 'BBN Banco Brasileiro de Negócios S.A',
        ]))->save();

        (new Banco([
            'numero' => '82',
            'fantasia' => 'Banco Topázio',
            'razao_social' => 'Banco Topázio S.A',
        ]))->save();

        (new Banco([
            'numero' => '83',
            'fantasia' => 'Banco da China Brasil',
            'razao_social' => 'Banco da China Brasil S.A.',
        ]))->save();

        (new Banco([
            'numero' => '84',
            'fantasia' => 'Uniprime Norte do Paraná',
            'razao_social' => 'Uniprime Norte do Paraná - Coop de Economia e Crédito Mútuo dos Médicos, Profissionais das Ciências',
        ]))->save();

        (new Banco([
            'numero' => '92',
            'fantasia' => 'Brickell S.A. Crédito, Financiamento e Investimento',
            'razao_social' => 'Brickell S.A. Crédito, Financiamento e Investimento',
        ]))->save();

        (new Banco([
            'numero' => '94',
            'fantasia' => 'Banco Finaxis',
            'razao_social' => 'Banco Finaxis S.A.',
        ]))->save();

        (new Banco([
            'numero' => '95',
            'fantasia' => 'Banco Confidence de Câmbio S.A.',
            'razao_social' => 'Banco Confidence de Câmbio S.A.',
        ]))->save();

        (new Banco([
            'numero' => '96',
            'fantasia' => 'Banco BM&FBOVESPA',
            'razao_social' => 'Banco BM&FBOVESPA de Serviços de Liquidação e Custódia S.A',
        ]))->save();

        (new Banco([
            'numero' => '97',
            'fantasia' => 'Cooperativa Central de Crédito Noroeste Brasileiro Ltda.',
            'razao_social' => 'Cooperativa Central de Crédito Noroeste Brasileiro Ltda.',
        ]))->save();

        (new Banco([
            'numero' => '104',
            'fantasia' => 'Caixa Econômica Federal',
            'razao_social' => 'Caixa Econômica Federal',
            'agencia_mascara' => '9999',
            'conta_mascara' => '99.999.999-9',
        ]))->save();

        (new Banco([
            'numero' => '107',
            'fantasia' => 'Banco BBM',
            'razao_social' => 'Banco BBM S.A.',
        ]))->save();

        (new Banco([
            'numero' => '118',
            'fantasia' => 'Standard Chartered Bank (Brasil)',
            'razao_social' => 'Standard Chartered Bank (Brasil) S/A–Bco Invest.',
        ]))->save();

        (new Banco([
            'numero' => '119',
            'fantasia' => 'Banco Western Union do Brasil',
            'razao_social' => 'Banco Western Union do Brasil S.A.',
        ]))->save();

        (new Banco([
            'numero' => '120',
            'fantasia' => 'Banco Rodobens',
            'razao_social' => 'Banco Rodobens S.A.',
        ]))->save();

        (new Banco([
            'numero' => '121',
            'fantasia' => 'Banco Agiplan',
            'razao_social' => 'Banco Agiplan S.A.',
        ]))->save();

        (new Banco([
            'numero' => '122',
            'fantasia' => 'Banco Bradesco BERJ',
            'razao_social' => 'Banco Bradesco BERJ S.A.',
        ]))->save();

        (new Banco([
            'numero' => '124',
            'fantasia' => 'Banco Woori Bank do Brasil',
            'razao_social' => 'Banco Woori Bank do Brasil S.A.',
        ]))->save();

        (new Banco([
            'numero' => '125',
            'fantasia' => 'Brasil Plural',
            'razao_social' => 'Brasil Plural S.A. - Banco Múltiplo',
        ]))->save();

        (new Banco([
            'numero' => '126',
            'fantasia' => 'BR Partners',
            'razao_social' => 'BR Partners Banco de Investimento S.A.',
        ]))->save();

        (new Banco([
            'numero' => '128',
            'fantasia' => 'MS Bank',
            'razao_social' => 'MS Bank S.A. Banco de Câmbio',
        ]))->save();

        (new Banco([
            'numero' => '129',
            'fantasia' => 'UBS Brasil',
            'razao_social' => 'UBS Brasil Banco de Investimento S.A.',
            ]))->save();
            
        (new Banco([
            'numero' => '132',
            'fantasia' => 'ICBC do Brasil',
            'razao_social' => 'ICBC do Brasil Banco Múltiplo S.A.',
        ]))->save();

        (new Banco([
            'numero' => '135',
            'fantasia' => 'Gradual Corretora de Câmbio,Títulos e Valores Mobiliários',
            'razao_social' => 'Gradual Corretora de Câmbio,Títulos e Valores Mobiliários S.A.',
        ]))->save();

        (new Banco([
            'numero' => '136',
            'fantasia' => 'UNICREDS',
            'razao_social' => 'CONFEDERACAO NACIONAL DAS COOPERATIVAS CENTRAIS UNICREDS',
        ]))->save();

        (new Banco([
            'numero' => '139',
            'fantasia' => 'Intesa Sanpaolo Brasil',
            'razao_social' => 'Intesa Sanpaolo Brasil S.A. - Banco Múltiplo',
        ]))->save();

        (new Banco([
            'numero' => '144',
            'fantasia' => 'BEXS',
            'razao_social' => 'BEXS Banco de Câmbio S.A',
        ]))->save();

        (new Banco([
            'numero' => '163',
            'fantasia' => 'Commerzbank Brasil',
            'razao_social' => 'Commerzbank Brasil S.A. - Banco Múltiplo',
        ]))->save();

        (new Banco([
            'numero' => '169',
            'fantasia' => 'Banco Olé Bonsucesso',
            'razao_social' => 'Banco Olé Bonsucesso Consignado S.A.',
        ]))->save();

        (new Banco([
            'numero' => '184',
            'fantasia' => 'Banco Itaú BBA',
            'razao_social' => 'Banco Itaú BBA S.A.',
        ]))->save();

        (new Banco([
            'numero' => '204',
            'fantasia' => 'Bradesco Cartões',
            'razao_social' => 'Banco Bradesco Cartões S.A..',
        ]))->save();

        (new Banco([
            'numero' => '208',
            'fantasia' => 'BTG Pactual',
            'razao_social' => 'Banco BTG Pactual S.A',
        ]))->save();

        (new Banco([
            'numero' => '212',
            'fantasia' => 'Banco Original',
            'razao_social' => 'Banco Original S.A.',
        ]))->save();

        (new Banco([
            'numero' => '213',
            'fantasia' => 'Banco Arbi',
            'razao_social' => 'Banco Arbi S.A',
        ]))->save();

        (new Banco([
            'numero' => '214',
            'fantasia' => 'Banco Dibens',
            'razao_social' => 'Banco Dibens S.A.',
        ]))->save();

        (new Banco([
            'numero' => '217',
            'fantasia' => 'Banco John Deere',
            'razao_social' => 'Banco John Deere S.A.',
        ]))->save();

        (new Banco([
            'numero' => '218',
            'fantasia' => 'Banco BS2',
            'razao_social' => 'Banco BS2 S.A.',
        ]))->save();

        (new Banco([
            'numero' => '222',
            'fantasia' => 'Banco Credit Agricole Brasil',
            'razao_social' => 'Banco Credit Agricole Brasil S.A.',
        ]))->save();

        (new Banco([
            'numero' => '224',
            'fantasia' => 'Banco Fibra S.A.',
            'razao_social' => 'Banco Fibra S.A.',
        ]))->save();

        (new Banco([
            'numero' => '225',
            'fantasia' => 'Banco Brascan',
            'razao_social' => 'Banco Brascan S.A.',
        ]))->save();

        (new Banco([
            'numero' => '229',
            'fantasia' => 'Banco Cruzeiro do Sul',
            'razao_social' => 'Banco Cruzeiro do Sul S.A.',
        ]))->save();

        (new Banco([
            'numero' => '230',
            'fantasia' => 'Unicard',
            'razao_social' => 'Unicard Banco Múltiplo S.A.',
        ]))->save();

        (new Banco([
            'numero' => '233',
            'fantasia' => 'Banco Cifra',
            'razao_social' => 'Banco Cifra S.A.',
        ]))->save();

        (new Banco([
            'numero' => '237',
            'fantasia' => 'Bradesco',
            'razao_social' => 'Banco Bradesco S.A.',
            'agencia_mascara' => '9999',
            'conta_mascara' => '9.999.999-9',
        ]))->save();

        (new Banco([
            'numero' => '241',
            'fantasia' => 'Banco Clássico S.A.',
            'razao_social' => 'Banco Clássico S.A.',
        ]))->save();

        (new Banco([
            'numero' => '243',
            'fantasia' => 'Banco Máxima S.A.',
            'razao_social' => 'Banco Máxima S.A.',
        ]))->save();

        (new Banco([
            'numero' => '246',
            'fantasia' => 'Banco ABC Brasil',
            'razao_social' => 'Banco ABC Brasil S.A.',
        ]))->save();

        (new Banco([
            'numero' => '248',
            'fantasia' => 'Banco Boavista Interatlântico',
            'razao_social' => 'Banco Boavista Interatlântico S.A.',
        ]))->save();

        (new Banco([
            'numero' => '249',
            'fantasia' => 'Banco Investcred Unibanco',
            'razao_social' => 'Banco Investcred Unibanco S.A.',
        ]))->save();

        (new Banco([
            'numero' => '250',
            'fantasia' => 'BCV - Banco de Crédito e Varejo',
            'razao_social' => 'BCV - Banco de Crédito e Varejo S.A.',
        ]))->save();

        (new Banco([
            'numero' => '254',
            'fantasia' => 'Paraná Banco',
            'razao_social' => 'Paraná Banco S.A.',
        ]))->save();

        (new Banco([
            'numero' => '260',
            'fantasia' => 'Nu Pagamentos',
            'razao_social' => 'Nu Pagamentos S.A.',
            'agencia_mascara' => '\\0\\0\\0\\1',
            'conta_mascara' => '9999999-9',
        ]))->save();

        (new Banco([
            'numero' => '263',
            'fantasia' => 'Banco Cacique',
            'razao_social' => 'Banco Cacique S.A.',
        ]))->save();

        (new Banco([
            'numero' => '265',
            'fantasia' => 'Banco Fator S.A.',
            'razao_social' => 'Banco Fator S.A.',
        ]))->save();

        (new Banco([
            'numero' => '266',
            'fantasia' => 'Banco Cédula',
            'razao_social' => 'Banco Cédula S.A.',
        ]))->save();

        (new Banco([
            'numero' => '300',
            'fantasia' => 'Banco de La Nacion Argentina',
            'razao_social' => 'Banco de La Nacion Argentina',
        ]))->save();

        (new Banco([
            'numero' => '318',
            'fantasia' => 'Banco BMG S.A.',
            'razao_social' => 'Banco BMG S.A.',
        ]))->save();

        (new Banco([
            'numero' => '320',
            'fantasia' => 'China Construction Bank (Brasil)',
            'razao_social' => 'China Construction Bank (Brasil) Banco Múltiplo S.A.',
        ]))->save();

        (new Banco([
            'numero' => '341',
            'fantasia' => 'Itaú Unibanco',
            'razao_social' => 'Itaú Unibanco S.A.',
            'agencia_mascara' => '9999',
            'conta_mascara' => '99.999-9',
        ]))->save();
        
        (new Banco([
            'numero' => '366',
            'fantasia' => 'Banco Société Générale Brasil',
            'razao_social' => 'Banco Société Générale Brasil S.A',
        ]))->save();

        (new Banco([
            'numero' => '370',
            'fantasia' => 'Banco Mizuho do Brasil',
            'razao_social' => 'Banco Mizuho do Brasil S.A.',
        ]))->save();
        
        (new Banco([
            'numero' => '376',
            'fantasia' => 'Banco J. P. Morgan',
            'razao_social' => 'Banco J. P. Morgan S.A.',
        ]))->save();

        (new Banco([
            'numero' => '389',
            'fantasia' => 'Banco Mercantil do Brasil',
            'razao_social' => 'Banco Mercantil do Brasil S.A.',
        ]))->save();

        (new Banco([
            'numero' => '394',
            'fantasia' => 'Banco Bradesco Financiamentos S.A.',
            'razao_social' => 'Banco Bradesco Financiamentos S.A.',
        ]))->save();

        (new Banco([
            'numero' => '399',
            'fantasia' => 'Kirton Bank',
            'razao_social' => 'Kirton Bank S.A. - Banco Múltiplo',
        ]))->save();

        (new Banco([
            'numero' => '409',
            'fantasia' => 'UNIBANCO',
            'razao_social' => 'UNIBANCO - União de Bancos Brasileiros S.A.',
        ]))->save();

        (new Banco([
            'numero' => '412',
            'fantasia' => 'Banco Capital',
            'razao_social' => 'Banco Capital S.A.',
        ]))->save();

        (new Banco([
            'numero' => '422',
            'fantasia' => 'Banco Safra',
            'razao_social' => 'Banco Safra S.A.',
        ]))->save();

        (new Banco([
            'numero' => '453',
            'fantasia' => 'Banco Rural',
            'razao_social' => 'Banco Rural S.A.',
        ]))->save();

        (new Banco([
            'numero' => '456',
            'fantasia' => 'Banco de Tokyo-Mitsubishi UFJ Brasil',
            'razao_social' => 'Banco de Tokyo-Mitsubishi UFJ Brasil S.A',
        ]))->save();

        (new Banco([
            'numero' => '464',
            'fantasia' => 'Banco Sumitomo Mitsui Brasileiro',
            'razao_social' => 'Banco Sumitomo Mitsui Brasileiro S.A.',
        ]))->save();

        (new Banco([
            'numero' => '473',
            'fantasia' => 'Banco Caixa Geral',
            'razao_social' => 'Banco Caixa Geral - Brasil S.A.',
        ]))->save();

        (new Banco([
            'numero' => '477',
            'fantasia' => 'Citibank',
            'razao_social' => 'Citibank N.A.',
        ]))->save();

        (new Banco([
            'numero' => '479',
            'fantasia' => 'ItauBank',
            'razao_social' => 'Banco ItauBank S.A.',
        ]))->save();

        (new Banco([
            'numero' => '487',
            'fantasia' => 'Deutsche Bank',
            'razao_social' => 'Deutsche Bank S.A. - Banco Alemão',
        ]))->save();

        (new Banco([
            'numero' => '488',
            'fantasia' => 'JPMorgan Chase Bank, National Association',
            'razao_social' => 'JPMorgan Chase Bank, National Association',
        ]))->save();

        (new Banco([
            'numero' => '492',
            'fantasia' => 'ING Bank N.V',
            'razao_social' => 'ING Bank N.V',
        ]))->save();

        (new Banco([
            'numero' => '494',
            'fantasia' => 'Banco de La Republica Oriental del Uruguay',
            'razao_social' => 'Banco de La Republica Oriental del Uruguay',
        ]))->save();

        (new Banco([
            'numero' => '495',
            'fantasia' => 'Banco de La Provincia de Buenos Aires',
            'razao_social' => 'Banco de La Provincia de Buenos Aires',
        ]))->save();

        (new Banco([
            'numero' => '505',
            'fantasia' => 'Banco Credit Suisse',
            'razao_social' => 'Banco Credit Suisse (Brasil) S.A.',
        ]))->save();

        (new Banco([
            'numero' => '600',
            'fantasia' => 'Banco Luso Brasileiro',
            'razao_social' => 'Banco Luso Brasileiro S.A.',
        ]))->save();

        (new Banco([
            'numero' => '604',
            'fantasia' => 'Banco Industrial do Brasil',
            'razao_social' => 'Banco Industrial do Brasil S.A.',
        ]))->save();

        (new Banco([
            'numero' => '610',
            'fantasia' => 'Banco VR',
            'razao_social' => 'Banco VR S.A.',
        ]))->save();

        (new Banco([
            'numero' => '611',
            'fantasia' => 'Banco Paulista',
            'razao_social' => 'Banco Paulista S.A.',
        ]))->save();

        (new Banco([
            'numero' => '612',
            'fantasia' => 'Banco Guanabara',
            'razao_social' => 'Banco Guanabara S.A.',
        ]))->save();

        (new Banco([
            'numero' => '613',
            'fantasia' => 'Banco Pecúnia',
            'razao_social' => 'Banco Pecúnia S.A.',
        ]))->save();

        (new Banco([
            'numero' => '623',
            'fantasia' => 'Banco PAN',
            'razao_social' => 'Banco PAN S.A.',
        ]))->save();

        (new Banco([
            'numero' => '626',
            'fantasia' => 'Banco Ficsa',
            'razao_social' => 'Banco Ficsa S.A.',
        ]))->save();

        (new Banco([
            'numero' => '630',
            'fantasia' => 'Banco Intercap',
            'razao_social' => 'Banco Intercap S.A.',
        ]))->save();

        (new Banco([
            'numero' => '633',
            'fantasia' => 'Banco Rendimento',
            'razao_social' => 'Banco Rendimento S.A.',
        ]))->save();

        (new Banco([
            'numero' => '634',
            'fantasia' => 'Banco Triângulo',
            'razao_social' => 'Banco Triângulo S.A.',
        ]))->save();

        (new Banco([
            'numero' => '637',
            'fantasia' => 'Sofisa',
            'razao_social' => 'Banco Sofisa S.A.',
        ]))->save();

        (new Banco([
            'numero' => '638',
            'fantasia' => 'Banco Prosper',
            'razao_social' => 'Banco Prosper S.A.',
        ]))->save();

        (new Banco([
            'numero' => '641',
            'fantasia' => 'Banco Alvorada',
            'razao_social' => 'Banco Alvorada S.A.',
        ]))->save();

        (new Banco([
            'numero' => '643',
            'fantasia' => 'Banco Pine',
            'razao_social' => 'Banco Pine S.A.',
        ]))->save();

        (new Banco([
            'numero' => '652',
            'fantasia' => 'Itaú Unibanco Holding',
            'razao_social' => 'Itaú Unibanco Holding S.A.',
        ]))->save();

        (new Banco([
            'numero' => '653',
            'fantasia' => 'Banco Indusval',
            'razao_social' => 'Banco Indusval S.A.',
        ]))->save();

        (new Banco([
            'numero' => '654',
            'fantasia' => 'Banco A.J.Renner',
            'razao_social' => 'Banco A.J.Renner S.A.',
        ]))->save();

        (new Banco([
            'numero' => '655',
            'fantasia' => 'Banco Votorantim',
            'razao_social' => 'Banco Votorantim S.A.',
        ]))->save();

        (new Banco([
            'numero' => '658',
            'fantasia' => 'Banco Porto Real de Investimentos',
            'razao_social' => 'Banco Porto Real de Investimentos S.A.',
        ]))->save();

        (new Banco([
            'numero' => '707',
            'fantasia' => 'Banco Daycoval',
            'razao_social' => 'Banco Daycoval S.A.',
        ]))->save();

        (new Banco([
            'numero' => '712',
            'fantasia' => 'Banco Ourinvest',
            'razao_social' => 'Banco Ourinvest S.A.',
        ]))->save();

        (new Banco([
            'numero' => '719',
            'fantasia' => 'Banif-Banco Internacional do Funchal (Brasil)',
            'razao_social' => 'Banif-Banco Internacional do Funchal (Brasil)S.A.',
        ]))->save();

        (new Banco([
            'numero' => '720',
            'fantasia' => 'Banco Maxinvest',
            'razao_social' => 'Banco Maxinvest S.A.',
        ]))->save();

        (new Banco([
            'numero' => '721',
            'fantasia' => 'Banco Credibel',
            'razao_social' => 'Banco Credibel S.A.',
        ]))->save();

        (new Banco([
            'numero' => '724',
            'fantasia' => 'Banco Porto Seguro',
            'razao_social' => 'Banco Porto Seguro S.A.',
        ]))->save();

        (new Banco([
            'numero' => '735',
            'fantasia' => 'Banco Neon',
            'razao_social' => 'Banco Neon S.A.',
        ]))->save();

        (new Banco([
            'numero' => '739',
            'fantasia' => 'Cetelem',
            'razao_social' => 'Banco Cetelem S.A.',
        ]))->save();

        (new Banco([
            'numero' => '741',
            'fantasia' => 'Banco Ribeirão Preto',
            'razao_social' => 'Banco Ribeirão Preto S.A.',
        ]))->save();

        (new Banco([
            'numero' => '743',
            'fantasia' => 'Banco Semear',
            'razao_social' => 'Banco Semear S.A.',
        ]))->save();

        (new Banco([
            'numero' => '744',
            'fantasia' => 'BankBoston',
            'razao_social' => 'BankBoston N.A.',
        ]))->save();

        (new Banco([
            'numero' => '745',
            'fantasia' => 'Banco Citibank',
            'razao_social' => 'Banco Citibank S.A.',
        ]))->save();

        (new Banco([
            'numero' => '746',
            'fantasia' => 'Banco Modal',
            'razao_social' => 'Banco Modal S.A.',
        ]))->save();

        (new Banco([
            'numero' => '747',
            'fantasia' => 'Banco Rabobank International Brasil',
            'razao_social' => 'Banco Rabobank International Brasil S.A.',
        ]))->save();

        (new Banco([
            'numero' => '748',
            'fantasia' => 'Sicredi',
            'razao_social' => 'Banco Cooperativo Sicredi S.A.',
        ]))->save();

        (new Banco([
            'numero' => '749',
            'fantasia' => 'Banco Simples',
            'razao_social' => 'Banco Simples S.A.',
        ]))->save();

        (new Banco([
            'numero' => '751',
            'fantasia' => 'Scotiabank',
            'razao_social' => 'Scotiabank Brasil S.A. Banco Múltiplo',
        ]))->save();

        (new Banco([
            'numero' => '752',
            'fantasia' => 'Banco BNP Paribas',
            'razao_social' => 'Banco BNP Paribas Brasil S.A.',
        ]))->save();

        (new Banco([
            'numero' => '753',
            'fantasia' => 'Novo Banco Continental',
            'razao_social' => 'Novo Banco Continental S.A. - Banco Múltiplo',
        ]))->save();

        (new Banco([
            'numero' => '755',
            'fantasia' => 'Bank of America Merrill Lynch',
            'razao_social' => 'Bank of America Merrill Lynch Banco Múltiplo S.A.',
        ]))->save();

        (new Banco([
            'numero' => '756',
            'fantasia' => 'BANCOOB',
            'razao_social' => 'Banco Cooperativo do Brasil S.A. - BANCOOB',
        ]))->save();

        (new Banco([
            'numero' => '757',
            'fantasia' => 'Banco KEB HANA',
            'razao_social' => 'Banco KEB HANA do Brasil S.A.',
        ]))->save();

        (new Banco([
            'numero' => '085-x',
            'fantasia' => 'CECRED',
            'razao_social' => 'Cooperativa Central de Crédito Urbano-CECRED',
        ]))->save();

        (new Banco([
            'numero' => '086-8',
            'fantasia' => 'OBOE Crédito Financiamento e Investimento',
            'razao_social' => 'OBOE Crédito Financiamento e Investimento S.A.',
        ]))->save();

        (new Banco([
            'numero' => '087-6',
            'fantasia' => 'Cooperativa Central de Economia e Crédito Mútuo das Unicreds de Santa Catarina e Paraná',
            'razao_social' => 'Cooperativa Central de Economia e Crédito Mútuo das Unicreds de Santa Catarina e Paraná.',
        ]))->save();

        (new Banco([
            'numero' => '089-2',
            'fantasia' => 'Cooperativa de Crédito Rural da Região da Mogiana',
            'razao_social' => 'Cooperativa de Crédito Rural da Região da Mogiana',
        ]))->save();

        (new Banco([
            'numero' => '090-2',
            'fantasia' => 'SICOOB',
            'razao_social' => 'Cooperativa Central de Economia e Crédito Mutuo - SICOOB UNIMAIS',
        ]))->save();

        (new Banco([
            'numero' => '091-4',
            'fantasia' => 'Unicred',
            'razao_social' => 'Unicred Central do Rio Grande do Sul',
        ]))->save();

        (new Banco([
            'numero' => '098-1',
            'fantasia' => 'CREDIALIANÇA',
            'razao_social' => 'CREDIALIANÇA COOPERATIVA DE CRÉDITO RURAL',
        ]))->save();

        (new Banco([
            'numero' => '114-7',
            'fantasia' => 'Central das Cooperativas de Economia e Crédito Mútuo do Estado do Espírito Santo',
            'razao_social' => 'Central das Cooperativas de Economia e Crédito Mútuo do Estado do Espírito Santo Ltda.',
        ]))->save();
    }
}
