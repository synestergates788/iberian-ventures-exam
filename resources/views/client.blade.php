@extends('templates.app')

@section('content')
    <form>
    <div class="row">
        <div class="col-md-12">
            <div class="decision"></div>
            <div class="form-group row">
                <label for="staticEmail" class="col-sm-4 col-form-label">Sector en el que opera tu empresa:</label>
                <div class="col-sm-8">
                    <input required type="text" class="form-control" id="company_sector">
                </div>
            </div>
            <div class="form-group row">
                <label for="staticEmail" class="col-sm-4 col-form-label">Correo electrónico de contacto:</label>
                <div class="col-sm-8">
                    <input required type="text" class="form-control" id="contact_email">
                </div>
            </div>
            <div class="form-group row">
                <label for="staticEmail" class="col-sm-4 col-form-label">Facturación media de los últimos 3 años (en €):</label>
                <div class="col-sm-8">
                    <input required type="text" class="force_to_int form-control" id="revenue">
                </div>
            </div>
            <div class="form-group row">
                <label for="staticEmail" class="col-sm-4 col-form-label">Años consecutivos creciendo ingreso:</label>
                <div class="col-sm-8">
                    <select class="form-control" id="yrs_of_growth">
                        <option value="0">0</option>
                        <option value="1">1</option>
                        <option value="2">2</option>
                        <option value="3">3</option>
                        <option value="3+">>3/option>
                    </select>
                </div>
            </div>
            <div class="form-group row">
                <label for="staticEmail" class="col-sm-4 col-form-label">EBITDA media de los últimos 3 años (en €):</label>
                <div class="col-sm-8">
                    <input required type="text" class="force_to_int form-control" id="avg_ebitda_last_three_yrs">
                </div>
            </div>
            <div class="form-group row">
                <label for="staticEmail" class="col-sm-4 col-form-label">Resultado neto medio de los últimos 3 años (en €):</label>
                <div class="col-sm-8">
                    <input required type="text" class="force_to_int form-control" id="avg_net_last_three_yrs">
                </div>
            </div>
            <div class="form-group row">
                <label for="staticEmail" class="col-sm-4 col-form-label">Años consecutivos con resultado positivo:</label>
                <div class="col-sm-8">
                    <select class="form-control" id="yrs_with_positive_net_result">
                        <option value="0">0</option>
                        <option value="1">1</option>
                        <option value="2">2</option>
                        <option value="3">3</option>
                        <option value="3+">>3/option>
                    </select>
                </div>
            </div>
            <div class="form-group row">
                <label for="staticEmail" class="col-sm-4 col-form-label">Deuda financiera neta total (en €):</label>
                <div class="col-sm-8">
                    <input required type="text" class="force_to_int form-control" id="net_debt">
                </div>
            </div>
            <div class="form-group row">
                <label for="staticEmail" class="col-sm-4 col-form-label">Total activo inmovilizado (en €):</label>
                <div class="col-sm-8">
                    <input required type="text" class="force_to_int form-control" id="fixed_assets">
                </div>
            </div>
            <div class="form-group row">
                <label for="staticEmail" class="col-sm-4 col-form-label">¿Porcentaje de la empresa del mayor accionista?:</label>
                <div class="col-sm-8">
                    <input required type="text" class="force_to_int form-control" id="biggest_shareholder" placeholder="%">
                </div>
            </div>
            <div class="form-group row">
                <label for="staticEmail" class="col-sm-4 col-form-label">Porcentaje de facturación que viene del mayor cliente:</label>
                <div class="col-sm-8">
                    <input required type="text" class="force_to_int form-control" id="revenue_from_biggest_client" placeholder="%">
                </div>
            </div>
            <div class="form-group row">
                <label for="staticEmail" class="col-sm-4 col-form-label">¿Ha sido auditada la compañía alguna vez?:</label>
                <div class="col-sm-8">
                    <select class="form-control" id="is_the_company_audited">
                        <option value="No">No</option>
                        <option value="Yes">Si</option>
                    </select>
                </div>
            </div>
            <div class="form-group row">
                <label for="staticEmail" class="col-sm-4 col-form-label">¿Operaciones de compra o fusiones en los últimos 5 años?</label>
                <div class="col-sm-8">
                    <select class="form-control" id="m_and_a_in_last_5_yrs">
                        <option value="No">No</option>
                        <option value="Yes">Si</option>
                    </select>
                </div>
            </div>
            <div class="form-group row">
                <label for="staticEmail" class="col-sm-4 col-form-label">¿Se quiere vender más del 90% de la compañía?</label>
                <div class="col-sm-8">
                    <select class="form-control" id="selling_90_percent">
                        <option value="No">No</option>
                        <option value="Yes">Si</option>
                    </select>
                </div>
            </div>
            <div class="form-group row">
                <label for="staticEmail" class="col-sm-4 col-form-label"></label>
                <div class="col-sm-8">
                    <button class="btn btn-submit btn-primary" id="save">Enviar</button>
                </div>
            </div>
        </div>
    </div>
    </form>
@endsection