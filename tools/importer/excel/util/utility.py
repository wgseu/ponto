# -*- coding: utf-8 -*-
import re
import time
from datetime import datetime
import codecs
import roman
import MySQLdb
import locale
import unicodedata

def strip_accents(input_str):
	if not input_str:
		return input_str
	nfkd_form = unicodedata.normalize('NFKD', input_str)
	only_ascii = nfkd_form.encode('ASCII', 'ignore')
	return only_ascii

def detect_gender(nome):
	p = re.compile(r'(?:a|ne|mem|lem|de|te|ly|ny|lu|eth|en)$', re.I)
	if p.search(nome):
		return 'Feminino'
	p = re.compile(r'(?:o|os|on|me|ur|el|us|x)$', re.I)
	if p.search(nome):
		return 'Masculino'
	# não detectado
	return 'Masculino'

def find_columns(ws, regex, col=1, row=1):
	p = re.compile(regex, re.I)
	columns = []
	while True:
		value = strip_accents(ws.cell(column=col, row=row).value)
		if not value:
			break;
		if p.search(value):
			# print value
			columns.append(col)
		col += 1
	return columns

def sql_field(value, escape='"'):
	if value == None:
		return "NULL"
	return escape + MySQLdb.escape_string(str(value)) + escape

def sql_float(value):
	if value == None:
		return "NULL"
	if isinstance(value, basestring):
		default = locale.getlocale(locale.LC_NUMERIC)
		locale.setlocale(locale.LC_NUMERIC, 'French_Canada.1252')
		value = locale.atof(value)
		locale.setlocale(locale.LC_NUMERIC, default)
	return str(value)

def sql_int(value):
	if value == None:
		return "NULL"
	if isinstance(value, basestring):
		default = locale.getlocale(locale.LC_NUMERIC)
		locale.setlocale(locale.LC_NUMERIC, 'French_Canada.1252')
		value = locale.atoi(value)
		locale.setlocale(locale.LC_NUMERIC, default)
	return str(value)

def sql_bool(value, default='Y'):
	if value == 0:
		value = 'N'
	if value == 1:
		value = 'Y'
	if value == "sim" or value == "Sim" or value == "SIM" or value == "s" or value == "S":
		value = 'Y'
	if value == "nao":
		value = 'N'
	if value != 'Y' and value != 'N':
		value = None
	if value == None:
		return "'" + default + "'"
	return "'" + value + "'"

def sql_datetime(value, default='NOW()'):
	if not value:
		return default
	if isinstance(value, datetime):
		return '"' + value.strftime("%Y-%m-%d %H:%M:%S") + '"'
	try:
		p = re.compile('[\d]{2}/[\d]{2}/[\d]{4} [\d]{2}:[\d]{2}:[\d]{2}', re.I)
		if p.search(value):
			t = datetime.strptime(value, "%d/%m/%Y %H:%M:%S")
			return '"' + t.strftime("%Y-%m-%d %H:%M:%S") + '"'

		p = re.compile('[\d]{2}-[\d]{2}-[\d]{4} [\d]{2}:[\d]{2}:[\d]{2}', re.I)
		if p.search(value):
			t = datetime.strptime(value, "%d-%m-%Y %H:%M:%S")
			return '"' + t.strftime("%Y-%m-%d %H:%M:%S") + '"'

		p = re.compile('[\d]{4}/[\d]{2}/[\d]{2} [\d]{2}:[\d]{2}:[\d]{2}', re.I)
		if p.search(value):
			t = datetime.strptime(value, "%Y/%m/%d %H:%M:%S")
			return '"' + t.strftime("%Y-%m-%d %H:%M:%S") + '"'

		p = re.compile('[\d]{4}-[\d]{2}-[\d]{2} [\d]{2}:[\d]{2}:[\d]{2}', re.I)
		if p.search(value):
			t = datetime.strptime(value, "%Y-%m-%d %H:%M:%S")
			return '"' + t.strftime("%Y-%m-%d %H:%M:%S") + '"'

		p = re.compile('[\d]{2}/[\d]{2}/[\d]{4}', re.I)
		if p.search(value):
			t = datetime.strptime(value, "%d/%m/%Y")
			return '"' + t.strftime("%Y-%m-%d %H:%M:%S") + '"'

		p = re.compile('[\d]{2}-[\d]{2}-[\d]{4}', re.I)
		if p.search(value):
			t = datetime.strptime(value, "%d-%m-%Y")
			return '"' + t.strftime("%Y-%m-%d %H:%M:%S") + '"'

		p = re.compile('[\d]{4}/[\d]{2}/[\d]{2}', re.I)
		if p.search(value):
			t = datetime.strptime(value, "%Y/%m/%d")
			return '"' + t.strftime("%Y-%m-%d %H:%M:%S") + '"'
			
		p = re.compile('[\d]{4}-[\d]{2}-[\d]{2}', re.I)
		if p.search(value):
			t = datetime.strptime(value, "%Y-%m-%d")
			return '"' + t.strftime("%Y-%m-%d %H:%M:%S") + '"'
	except:
		return default
	return default

def filter_digits(text):
	if not text:
		return None
	digits = re.sub(r'[^0-9]', '', str(text))
	if not digits:
		return None
	return digits

def filter_phone(phone, ddd):
	phone = filter_digits(phone)
	if not phone or len(phone) < 8 or len(phone) > 12:
		return None
	if len(phone) < 10:
		phone = ddd + phone
	return phone

def filter_name(nome):
	if not nome:
		return None
	p = re.compile(r'^(?:de|do|da|das|dos|a|o|e|ao|em|na|no)$', re.I)
	c = re.compile(r'^[b-df-hj-np-tv-z]+$', re.I)
	result = []
	i = 0
	components = str(nome).split(' ')
	for x in components:
		if not x:
			continue
		if c.search(x) or roman.romanNumeralPattern.search(unicode(x).upper()):
			result.append(unicode(x).upper())
		elif p.search(x) and i > 0:
			result.append(unicode(x).lower())
		else:
			result.append(unicode(x).title())
		i = i + 1
	return ' '.join(result)

def filter_gender(gender):
	if not gender:
		return None
	p = re.compile(r'^(?:m|masc|masculino|macho|homem)$', re.I)
	if p.search(gender):
		return 'Masculino'
	p = re.compile(r'^(?:f|fem|feminino|feminina|mulher|empresa)$', re.I)
	if p.search(gender):
		return 'Feminino'
	return None

def filter_email(text):
	if not text:
		return None
	match = re.match(r'^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,4})$', str(text))
	if not match:
		return None
	return str(text)

def filter_cpf(cpf):
	cpf = filter_digits(cpf)
	if not cpf or len(cpf) != 11:
		return None
	return cpf

def filter_cnpj(cnpj):
	cnpj = filter_digits(cnpj)
	if not cnpj or len(cnpj) != 14:
		return None
	return cnpj

def filter_zipcode(zipcode):
	zipcode = filter_digits(zipcode)
	if not zipcode or len(zipcode) != 8:
		return None
	return zipcode

def get_cell(ws, row, columns):
	if not columns:
		return None
	col = columns[0]
	value = ws.cell(column=col, row=row).value
	if isinstance(value, basestring):
		value = value.encode("utf-8")
	return value

def convert_encoding(sourceFileName, targetFileName, sourceEncoding="utf-8", destEncoding="cp1252"):
	BLOCKSIZE = 1048576 # or some other, desired size in bytes
	with codecs.open(sourceFileName, "r", sourceEncoding) as sourceFile:
	    with codecs.open(targetFileName, "w", destEncoding) as targetFile:
	        while True:
	            contents = sourceFile.read(BLOCKSIZE)
	            if not contents:
	                break
	            targetFile.write(contents)

