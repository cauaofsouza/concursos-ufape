<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\StoreConcursoRequest;

class Concurso extends Model
{
    use HasFactory;

    public const TIPO_ENUM = [
        'substituto'     => 0,
        'efetivo'     => 1,
    ];

    protected $fillable = [
        'titulo',
        'qtd_vagas',
        'descricao',
        'data_inicio_inscricao',
        'data_fim_inscricao',
        'data_fim_isencao_inscricao',
        'data_fim_pagamento_inscricao',
        'data_inicio_envio_doc',
        'data_fim_envio_doc',
        'data_resultado_selecao',
        'edital_geral',
        'edital_especifico',
        'declaracao_veracidade',
        'users_id',
        'tipo'
    ];

    public function inscricoes()
    {
        return $this->hasMany(Inscricao::class, 'concursos_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'users_id');
    }

    public function vagas()
    {
        return $this->hasMany(OpcoesVagas::class, 'concursos_id')->orderBy('nome');
    }

    public function setAtributes(StoreConcursoRequest $request)
    {
        $this->titulo                           = $request->input('título');
        $this->qtd_vagas                        = $request->quantidade_de_vagas;
        $this->descricao                        = $request->input('descrição');
        $this->data_inicio_inscricao            = $request->input('data_de_início_da_inscrição');
        $this->data_fim_inscricao               = $request->input('data_de_término_da_inscrição');
        $this->data_fim_isencao_inscricao       = $request->input('data_limite_para_isenção');
        $this->data_fim_pagamento_inscricao     = $request->data_limite_para_pagamento;
        $this->data_inicio_envio_doc            = $request->input('data_de_início_para_envio_dos_documentos');
        $this->data_fim_envio_doc               = $request->data_final_para_envio_dos_documentos;
        $this->data_resultado_selecao           = $request->data_do_resultado_do_concurso;
        $this->users_id                         = auth()->user()->id;
        $this->tipo                             = $request->input('tipo');
    }

    public function salvarArquivos(StoreConcursoRequest $request)
    {
        $path = 'concursos/' . $this->id . '/';

        if ($request->hasFile('edital_geral')) {
            $arquivo = $request->file('edital_geral');
            $nome = 'edital_geral.' . $arquivo->getClientOriginalExtension();
            Storage::disk('public')->putFileAs($path, $arquivo, $nome);
            $this->edital_geral = $path . $nome;
        }

        if ($request->hasFile('edital_específico')) {
            $arquivo = $request->file('edital_específico');
            $nome = 'edital_especifico.' . $arquivo->getClientOriginalExtension();
            Storage::disk('public')->putFileAs($path, $arquivo, $nome);
            $this->edital_especifico = $path . $nome;
        }

        if ($request->hasFile('declaração_de_veracidade')) {
            $arquivo = $request->file('declaração_de_veracidade');
            $nome = 'declaracao_de_veracidade.' . $arquivo->getClientOriginalExtension();
            Storage::disk('public')->putFileAs($path, $arquivo, $nome);
            $this->declaracao_veracidade = $path . $nome;
        }
    }

    public function deletar()
    {
        if (Storage::disk()->exists('public/' . $this->edital_geral)) {
            Storage::delete('public/' . $this->edital_geral);
        }

        if (Storage::disk()->exists('public/' . $this->edital_especifico)) {
            Storage::delete('public/' . $this->edital_especifico);
        }

        if (Storage::disk()->exists('public/' . $this->declaracao_veracidade)) {
            Storage::delete('public/' . $this->declaracao_veracidade);
        }

        $this->delete();
    }

    public function chefeDaBanca() {
        return $this->belongsToMany(User::class, 'chefe_da_banca', 'concursos_id', 'users_id')->withPivot('chefe');
    }

    public function notas() {
        return $this->hasMany(NotaDeTexto::class, 'concursos_id');
    }

    public function resultados() {
        return $this->hasMany(Resultado::class, 'concurso_id');
    }

    public function avaliacoesEfetivo() {
        return $this->hasMany(AvaliacaoEfetivo::class, 'concurso_id')->orderBy('status');
    }

    public function documentosExtras() {
        return $this->hasMany(DocumentoExtra::class, 'concurso_id');
    }

}
