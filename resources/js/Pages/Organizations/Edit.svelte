<script>
    import { onMount } from 'svelte'
    import { Inertia } from '@inertiajs/inertia';
    import { InertiaLink, page } from '@inertiajs/inertia-svelte';
    import { toFormData } from '@/utils';
    import axios from 'axios'

    import Helmet from '@/Shared/Helmet.svelte';
    import Layout from '@/Shared/Layout.svelte';
    import DeleteButton from '@/Shared/DeleteButton.svelte';
    import LoadingButton from '@/Shared/LoadingButton.svelte';
    import TextInput from '@/Shared/TextInput.svelte';
    import SelectInput from '@/Shared/SelectInput.svelte';
    import TrashedMessage from '@/Shared/TrashedMessage.svelte';
    import Icon from '@/Shared/Icon.svelte';
    import Pagination from '@/Shared/Pagination.svelte';
    import MoneyFormat from '@/Shared/MoneyFormat.svelte';
    import FileInput from '@/Shared/FileInput.svelte';

    const route = window.route;

    $: links = $page.properties.links;
    $: properties = $page.properties.data;
    let { organization } = $page;
    $: errors = $page.errors;
    $: organization = $page.organization;

    let countries = [];
    let departments = [];
    let cities = [];
    let sending = false;
    let values = {
            name: organization.name || '',
            email: organization.email || '',
            phone: organization.phone || '',
            address: organization.address || '',
            citie_id: organization.citie_id || '',
            deparment_id: organization.citie.department_id || '',
            countrie_id: organization.citie.department.countrie_id || '',
            postal_code: organization.postal_code || '',
            photo: ''
        };

    function handleChange({ target: { name, value } }) {
        values = {
            ...values,
            [name]: value
        };
    }

    function handleFileChange(file) {
        values = {
            ...values,
            photo: file
        };
    }

    function handleSubmit() {
        sending = true;

    // since we are uploading an image
    // we need to use FormData object

    // NOTE: When working with Laravel PUT/PATCH requests and FormData
    // you SHOULD send POST request and fake the PUT request like this.
    // For more info check utils.jf file
    const formData = toFormData(values, 'PUT');

        Inertia.post(route('organizations.update', organization.id), formData).then(() => sending = false);
    }

    function destroy() {
        if (confirm('Esta seguro de desactivar esta inmobiliaria?')) {
            Inertia.delete(route('organizations.destroy', organization.id));
        }
    }

    function restore() {
        if (confirm('Esta seguro de activar esta inmobiliaria?')) {
            Inertia.put(route('organizations.restore', organization.id));
        }
    }

    onMount(() => {
        axios.get(route('web-api.countries')).then((resp)=>{
            countries = resp.data;
        });
        getDeptos(values.countrie_id);
        getCities(values.deparment_id);
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

<Helmet title={values.name} />

<Layout>
    <div>
        <h1 class="mb-8 font-bold text-3xl">
            <InertiaLink
                href={route('organizations')}
                class="text-indigo-600 hover:text-indigo-700"
            >
                Inmobiliarias
            </InertiaLink>

            <span class="text-indigo-600 font-medium mx-2">/</span>
            {values.name}

            {#if organization.photo}
                <img class="block w-8 h-8 rounded-full ml-4" src={organization.photo} alt={organization.name} />
            {/if}
        </h1>

        {#if organization.deleted_at}
            <TrashedMessage onRestore={restore}>Esta inmobiliaria esta desactivada.</TrashedMessage>
        {/if}

        <div class="bg-white rounded shadow overflow-hidden max-w-3xl">
            <form on:submit|preventDefault={handleSubmit}>
                <div class="p-8 -mr-6 -mb-8 flex flex-wrap">
                    <TextInput
                        className="pr-6 pb-8 w-full lg:w-1/2"
                        label="Nombre"
                        name="name"
                        errors={errors.name}
                        bind:value={values.name}
                        onChange={handleChange}
                    />

                    <TextInput
                        className="pr-6 pb-8 w-full lg:w-1/2"
                        label="Correo"
                        name="email"
                        type="email"
                        errors={errors.email}
                        bind:value={values.email}
                        onChange={handleChange}
                    />

                    <TextInput
                        className="pr-6 pb-8 w-full lg:w-1/2"
                        label="Teléfono"
                        name="phone"
                        type="text"
                        errors={errors.phone}
                        bind:value={values.phone}
                        onChange={handleChange}
                    />

                    <TextInput
                        className="pr-6 pb-8 w-full lg:w-1/2"
                        label="Dirección"
                        name="address"
                        type="text"
                        errors={errors.address}
                        bind:value={values.address}
                        onChange={handleChange}
                    />

                    <SelectInput
                        className="pr-6 pb-8 w-full lg:w-1/2"
                        label="País"
                        name="countrie_id"
                        errors={errors.countrie_id}
                        bind:value={values.countrie_id}
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
                        bind:value={values.deparment_id}
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
                        bind:value={values.citie_id}
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
                        bind:value={values.postal_code}
                        onChange={handleChange}
                    />

                    <FileInput
                        className="pr-6 pb-8 w-full lg:w-1/2"
                        label="Photo"
                        name="photo"
                        accept="image/*"
                        errors={errors.photo}
                        value={values.photo}
                        onChange={handleFileChange}
                    />
                </div>

                <div class="px-8 py-4 bg-gray-100 border-t border-gray-200 flex items-center">
                    {#if !organization.deleted_at}
                        <DeleteButton onDelete={destroy}>Desactivar Inmobiliaria</DeleteButton>
                    {/if}

                    <LoadingButton
                        loading={sending}
                        type="submit"
                        className="btn-indigo ml-auto"
                    >
                        Actualizar Inmobiliaria
                    </LoadingButton>
                </div>
            </form>
        </div>
        
        <div>
            <h3 class="my-8 font-bold text-3xl">Propiedades</h3>
            <div class="mb-6 flex justify-between items-center">

                <InertiaLink
                    class="btn-indigo"
                    href={route('properties.create',organization.id)}
                >
                    <span>Crear</span>
                    <span class="hidden md:inline"> Propiedad</span>
                </InertiaLink>
            </div>

            <div class="bg-white rounded shadow overflow-x-auto">
                <table class="w-full whitespace-no-wrap">
                    <thead>
                        <tr class="text-left font-bold">
                            <th class="px-6 pt-5 pb-4">Nombre</th>
                            <th class="px-6 pt-5 pb-4">Ciudad</th>
                            <th class="px-6 pt-5 pb-4">Tipo Negocio</th>
                            <th class="px-6 pt-5 pb-4">Valor</th>
                        </tr>
                    </thead>
                    <tbody>
                        {#if !properties || properties.length === 0}
                            <tr>
                                <td class="border-t px-6 py-4" colspan="4">
                                    No se encontrarón propiedades.
                                </td>
                            </tr>
                        {:else}
                            {#each properties as pty (pty.id)}
                                <tr class="hover:bg-gray-100 focus-within:bg-gray-100">
                                    <td class="border-t">
                                        <InertiaLink
                                            href={route('properties.edit', {propertie:pty.id, organization: pty.organization_id})}
                                            class="px-6 py-4 flex items-center focus:text-indigo-700"
                                        >
                                            {pty.title}

                                            {#if pty.deleted_at}
                                                <Icon
                                                    name="trash"
                                                    className="flex-shrink-0 w-3 h-3 text-gray-400 fill-current ml-2"
                                                />
                                            {/if}
                                        </InertiaLink>
                                    </td>

                                    <td class="border-t">
                                        <InertiaLink
                                            tabindex="-1"
                                            href={route('properties.edit', {propertie:pty.id, organization: pty.organization_id})}
                                            class="px-6 py-4 flex items-center focus:text-indigo"
                                        >
                                            {pty.citie.name}
                                        </InertiaLink>
                                    </td>

                                    <td class="border-t">
                                        <InertiaLink
                                            tabindex="-1"
                                            href={route('properties.edit', {propertie:pty.id, organization: pty.organization_id})}
                                            class="px-6 py-4 flex items-center focus:text-indigo"
                                        >
                                            {pty.properties_type.name}
                                        </InertiaLink>
                                    </td>

                                    <td class="border-t">
                                        <InertiaLink
                                            tabindex="-1"
                                            href={route('properties.edit', {propertie:pty.id, organization: pty.organization_id})}
                                            class="px-6 py-4 flex items-center focus:text-indigo"
                                        >
                                            <MoneyFormat value={pty.value}></MoneyFormat>
                                        </InertiaLink>
                                    </td>

                                    <td class="border-t w-px">
                                        <InertiaLink
                                            tabindex="-1"
                                            href={route('properties.edit', {propertie:pty.id, organization: pty.organization_id})}
                                            class="px-4 flex items-center"
                                        >
                                            <Icon
                                                name="cheveron-right"
                                                className="block w-6 h-6 text-gray-400 fill-current"
                                            />
                                        </InertiaLink>
                                    </td>
                                </tr>
                            {/each}
                        {/if}
                    </tbody>
                </table>
            </div>

            <Pagination links={links} />
        </div>
    </div>
</Layout>
