$[table.each(unique)]
$[unique.each(all)]
    '$[table.unix].$[field.unix]_used' => '$[FIELD.gender] $[field.name] "%s" já está cadastrad$[field.gender] em outr$[table.gender] $[table.name]',
$[unique.end]
$[table.end]
$[field.each(all)]
$[field.if(primary)]
$[field.else.contains(fone)]
    '$[table.unix].$[field.unix]_invalid' => '$[FIELD.gender] $[Field.name] é inválid$[field.gender]',
$[field.else.match(usuario|login|email|ip|senha|password)]
    '$[table.unix].$[field.unix]_invalid' => '$[FIELD.gender] $[Field.name] é inválid$[field.gender]',
$[field.else.if(enum)]
    '$[table.unix].$[field.unix]_invalid' => '$[FIELD.gender] $[Field.name] é inválid$[field.gender]',
$[field.else.if(boolean)]
    '$[table.unix].$[field.unix]_invalid' => '$[FIELD.gender] $[Field.name] é inválid$[field.gender]',
$[field.else.match(cpf|cep|cnpj)]
    '$[field.unix]_invalid' => '$[FIELD.gender] %s é inválid$[field.gender]',
$[field.else.if(null)]
$[field.else]
    '$[table.unix].$[field.unix]_cannot_empty' => '$[FIELD.gender] $[field.name] não pode ser vazi$[field.gender]',
$[field.end]
$[field.end]
    '$[table.unix].id_cannot_empty' => 'O identificador d$[table.gender] $[table.name] não foi informado',
    '$[table.unix].not_found' => '$[Table.name] não encontrad$[table.gender]',
$[field.each(all)]
$[field.if(primary)]
$[field.else.if(enum)]
$[field.each(option)]
    '$[table.unix].$[field.unix]_$[field.option.unix]' => '$[Field.option.name]',
$[field.end]
$[field.end]
$[field.end]
