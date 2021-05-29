<script>
    import { onMount } from 'svelte'
    import { Inertia } from '@inertiajs/inertia';
    import { InertiaLink, page } from '@inertiajs/inertia-svelte';
    import axios from 'axios'

    import Helmet from '@/Shared/Helmet.svelte';
    import Layout from '@/Shared/Layout.svelte';
    import LoadingButton from '@/Shared/LoadingButton.svelte';
    import TextInput from '@/Shared/TextInput.svelte';
    import SelectInput from '@/Shared/SelectInput.svelte';

    const route = window.route;

    $: errors = $page.errors;

    let countries = [];
    let departments = [];
    let cities = [];
    let sending = false;
    let values = {
        name: '',
        email: '',
        phone: '',
        address: '',
        citie_id: '',
        deparment_id: '',
        countrie_id: '',
        postal_code: ''
    };

    function handleChange({ target: { name, value } }) {
        values = {
            ...values,
            [name]: value
        };
        if(name=='countrie_id'){getDeptos(value)}
        if(name=='deparment_id'){getCities(value)}
    }

    function handleSubmit() {
        sending = true;
        Inertia.post(route('organizations.store'), values).then(() => sending = false);
    }

    onMount(() => {
        axios.get(route('web-api.countries')).then((resp)=>{
            countries = resp.data;
        });
        departments = [];
        cities = [];
    })
    function getDeptos(id){
        axios.get(route('web-api.departments')+'?field=id&value='+id).then((resp)=>{
            departments = resp.data;
        });
        cities = [];
    }
    function getCities(id){
        axios.get(route('web-api.cities')+'?field=id&value='+id).then((resp)=>{
            cities = resp.data;
        });
    }
</script>

<Helmet title="Crear Inmobiliaria" />

<Layout>
    <div>
        <h1 class="mb-8 font-bold text-3xl">
            <InertiaLink
                href={route('organizations')}
                class="text-indigo-600 hover:text-indigo-700"
            >
            Inmobiliarias
            </InertiaLink>

            <span class="text-indigo-600 font-medium"> /</span> Crear
        </h1>

        <div class="bg-white rounded shadow overflow-hidden max-w-3xl">
            <form on:submit|preventDefault={handleSubmit}>
                <div class="p-8 -mr-6 -mb-8 flex flex-wrap">
                    <TextInput
                        className="pr-6 pb-8 w-full lg:w-1/2"
                        label="Nombre"
                        name="name"
                        errors={errors.name}
                        value={values.name}
                        onChange={handleChange}
                    />

                    <TextInput
                        className="pr-6 pb-8 w-full lg:w-1/2"
                        label="Correo"
                        name="email"
                        type="email"
                        errors={errors.email}
                        value={values.email}
                        onChange={handleChange}
                    />

                    <TextInput
                        className="pr-6 pb-8 w-full lg:w-1/2"
                        label="Teléfono"
                        name="phone"
                        type="text"
                        errors={errors.phone}
                        value={values.phone}
                        onChange={handleChange}
                    />

                    <TextInput
                        className="pr-6 pb-8 w-full lg:w-1/2"
                        label="Dirección"
                        name="address"
                        type="text"
                        errors={errors.address}
                        value={values.address}
                        onChange={handleChange}
                    />

                    <SelectInput
                        className="pr-6 pb-8 w-full lg:w-1/2"
                        label="País"
                        name="countrie_id"
                        errors={errors.countrie_id}
                        value={values.countrie_id}
                        onChange={handleChange}
                    >
                        <option value=""></option>
                        {#each countries as co (co.id)}
                        <option value="{co.id}">{co.name}</option>
                        {/each}
                    </SelectInput>

                    <SelectInput
                        className="pr-6 pb-8 w-full lg:w-1/2"
                        label="Departamento/Estado"
                        name="deparment_id"
                        errors={errors.deparment_id}
                        value={values.deparment_id}
                        onChange={handleChange}
                    >
                        <option value=""></option>
                        {#each departments as dep (dep.id)}
                        <option value="{dep.id}">{dep.name}</option>
                        {/each}
                    </SelectInput>

                    <SelectInput
                        className="pr-6 pb-8 w-full lg:w-1/2"
                        label="Ciudad"
                        name="citie_id"
                        errors={errors.citie_id}
                        value={values.citie_id}
                        onChange={handleChange}
                    >
                        <option value=""></option>
                        {#each cities as ct (ct.id)}
                        <option value="{ct.id}">{ct.name}</option>
                        {/each}
                    </SelectInput>

                    <TextInput
                        className="pr-6 pb-8 w-full lg:w-1/2"
                        label="Código postal"
                        name="postal_code"
                        type="text"
                        errors={errors.postal_code}
                        value={values.postal_code}
                        onChange={handleChange}
                    />
                </div>

                <div class="px-8 py-4 bg-gray-100 border-t border-gray-200 flex justify-end items-center">
                    <LoadingButton
                        loading={sending}
                        type="submit"
                        className="btn-indigo"
                    >
                        Crear Inmobiliaria
                    </LoadingButton>
                </div>
            </form>
        </div>
    </div>
</Layout>
