<script>
    import { onMount } from 'svelte'
    import { Inertia } from '@inertiajs/inertia';
    import { InertiaLink, page } from '@inertiajs/inertia-svelte';
    import { toFormData } from '@/utils';
    import axios from 'axios'
    import Map from "@anoram/leaflet-svelte";

    import Helmet from '@/Shared/Helmet.svelte';
    import Layout from '@/Shared/Layout.svelte';
    import DeleteButton from '@/Shared/DeleteButton.svelte';
    import LoadingButton from '@/Shared/LoadingButton.svelte';
    import TextInput from '@/Shared/TextInput.svelte';
    import TextArea from '@/Shared/TextArea.svelte';
    import SelectInput from '@/Shared/SelectInput.svelte';
    import TrashedMessage from '@/Shared/TrashedMessage.svelte';
    import FileInput from '@/Shared/FileInput.svelte';

    const route = window.route;

    export let propertie;
    export let types;
    export let owners;
    export let organization;

    $: errors = $page.errors;
    $: propertie = $page.propertie;
    $: types = $page.types;
    $: owners = $page.owners;
    $: organization = $page.organization;

    //map
    let options = {
        zoom: 14,
        center:[5.0504997,-75.4944],
        mapID: "map",
    };
    let MAP_EL;
	let latlng = ''
    async function init() {
        let map = MAP_EL.getMap();
        
        if(navigator.geolocation && (propertie.latitude==0 && propertie.longitude==0)) {
            navigator.geolocation.getCurrentPosition((position) => {
                let latitude = position.coords.latitude;
                let longitude = position.coords.longitude;
                map.setView([latitude,longitude],13);
            }, ()=>{}, {timeout:3000});
        }else if(propertie.latitude!=0 && propertie.longitude!=0){
            map.setView([propertie.latitude,propertie.longitude],13);
            latlng = L.latLng(propertie.latitude, propertie.longitude);
            L.popup()
            .setLatLng(latlng)
            .setContent(`<p>${values.title}<br>Ubicación: ${latlng}</p>`)
            .openOn(map);
        }

        map.on("click", function (e) {
        latlng = e.latlng
        values.latitude = e.latlng.lat;
        values.longitude = e.latlng.lng
        var popLocation = e.latlng;
        L.popup()
            .setLatLng(popLocation)
            .setContent(`<p>${values.title}<br>Ubicación: ${e.latlng}</p>`)
            .openOn(map);
        });
    }

    //data
    let countries = [];
    let departments = [];
    let cities = [];
    let sending = false;
    let values = {
        owner_id: propertie.owner_id || '',
        properties_type_id: propertie.properties_type_id || '',
        citie_id: propertie.citie_id || '',
        department_id: propertie.citie.department_id || '',
        countrie_id: propertie.citie.department.countrie_id || '',
        title: propertie.title || '',
        description: propertie.description || '',
        address: propertie.address || '',
        longitude: propertie.longitude || '',
        latitude: propertie.latitude || '',
        area: propertie.area || '',
        bedrooms: propertie.bedrooms || '',
        toilets: propertie.toilets || '',
        floor: propertie.floor || '',
        value: propertie.value || ''
    };

    function handleChange({ target: { name, value } }) {
        values = {
            ...values,
            [name]: value
        };
        if(name=='countrie_id'){getDeptos(value)}
        if(name=='deparment_id'){getCities(value)}
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

        Inertia.post(route('properties.update', {organization:organization.id, propertie:propertie.id}), formData).then(() => sending = false);
    }

    function destroy() {
        if (confirm('Esta seguro de desactivar esta propiedad?')) {
            Inertia.delete(route('properties.destroy', {organization:organization.id, propertie:propertie.id}));
        }
    }

    function restore() {
        if (confirm('Esta seguro de activar esta propiedad?')) {
            Inertia.put(route('properties.restore', {organization:organization.id, propertie:propertie.id}));
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
        axios.get(route('web-api.departments')+'?field=countrie_id&value='+id).then((resp)=>{
            departments = resp.data;
        });
        cities = [];
    }
    function getCities(id){
        axios.get(route('web-api.cities')+'?field=department_id&value='+id).then((resp)=>{
            cities = resp.data;
        });
    }
</script>

<Helmet title={values.title} />

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
            <InertiaLink
                href={route('organizations.edit', organization.id)}
                class="text-indigo-600 hover:text-indigo-700"
            >
                {organization.name}
            </InertiaLink>

            <span class="text-indigo-600 font-medium mx-2">/</span>
            {values.title}

            {#if propertie.photo}
                <img class="block w-8 h-8 rounded-full ml-4" src={propertie.photo} alt={propertie.name} />
            {/if}
        </h1>

        {#if propertie.deleted_at}
            <TrashedMessage onRestore={restore}>Esta propiedad esta desactivada.</TrashedMessage>
        {/if}

        <div class="bg-white rounded shadow overflow-hidden">
            <form on:submit|preventDefault={handleSubmit}>
                <div class="p-8 -mr-6 -mb-8 flex flex-wrap">
                    <TextInput
                        className="pr-6 pb-8 w-full lg:w-1/2"
                        label="Titulo"
                        name="title"
                        errors={errors.title}
                        value={values.title}
                        onChange={handleChange}
                        required
                    />

                    <TextInput
                        className="pr-6 pb-8 w-full lg:w-1/4"
                        label="Valor"
                        name="value"
                        type="number"
                        step="1"
                        errors={errors.value}
                        value={values.value}
                        onChange={handleChange}
                        required
                    />

                    <SelectInput
                        className="pr-6 pb-8 w-full lg:w-1/4"
                        label="Tipo"
                        name="properties_type_id"
                        errors={errors.properties_type_id}
                        value={values.properties_type_id}
                        onChange={handleChange}
                        required
                    >
                        <option value=""></option>
                        {#each types as pt (pt.id)}
                        <option value="{pt.id}">{pt.name}</option>
                        {/each}
                    </SelectInput>

                    <TextArea
                        className="pr-6 pb-8 w-full"
                        label="Descripción"
                        name="description"
                        errors={errors.description}
                        value={values.description}
                        onChange={handleChange}
                        required
                    />

                    <SelectInput
                        className="pr-6 pb-8 w-full lg:w-1/4"
                        label="País"
                        name="countrie_id"
                        errors={errors.countrie_id}
                        value={values.countrie_id}
                        onChange={handleChange}
                        required
                    >
                        <option value=""></option>
                        {#each countries as co (co.id)}
                        <option value="{co.id}">{co.name}</option>
                        {/each}
                    </SelectInput>

                    <SelectInput
                        className="pr-6 pb-8 w-full lg:w-1/4"
                        label="Departamento/Estado"
                        name="deparment_id"
                        errors={errors.deparment_id}
                        value={values.deparment_id}
                        onChange={handleChange}
                        required
                    >
                        <option value=""></option>
                        {#each departments as dep (dep.id)}
                        <option value="{dep.id}">{dep.name}</option>
                        {/each}
                    </SelectInput>

                    <SelectInput
                        className="pr-6 pb-8 w-full lg:w-1/4"
                        label="Ciudad"
                        name="citie_id"
                        errors={errors.citie_id}
                        value={values.citie_id}
                        onChange={handleChange}
                        required
                    >
                        <option value=""></option>
                        {#each cities as ct (ct.id)}
                        <option value="{ct.id}">{ct.name}</option>
                        {/each}
                    </SelectInput>

                    <TextInput
                        className="pr-6 pb-8 w-full lg:w-1/4"
                        label="Dirección"
                        name="address"
                        type="text"
                        errors={errors.address}
                        value={values.address}
                        onChange={handleChange}
                    />

                    <TextInput
                        className="pr-6 pb-8 w-full lg:w-1/12"
                        label="Área"
                        name="area"
                        type="number"
                        errors={errors.area}
                        value={values.area}
                        onChange={handleChange}
                    />

                    <TextInput
                        className="pr-6 pb-8 w-full lg:w-1/12"
                        label="Cuartos"
                        name="bedrooms"
                        type="number"
                        step="1"
                        errors={errors.bedrooms}
                        value={values.bedrooms}
                        onChange={handleChange}
                    />

                    <TextInput
                        className="pr-6 pb-8 w-full lg:w-1/12"
                        label="Baños"
                        name="toilets"
                        type="number"
                        step="1"
                        errors={errors.toilets}
                        value={values.toilets}
                        onChange={handleChange}
                    />

                    <TextInput
                        className="pr-6 pb-8 w-full lg:w-1/12"
                        label="Piso"
                        name="floor"
                        type="number"
                        step="1"
                        errors={errors.floor}
                        value={values.floor}
                        onChange={handleChange}
                    />

                    <SelectInput
                        className="pr-6 pb-8 w-full lg:w-1/4"
                        label="Propietario"
                        name="owner_id"
                        errors={errors.owner_id}
                        value={values.owner_id}
                        onChange={handleChange}
                        required
                    >
                        <option value=""></option>
                        {#each owners as ow (ow.id)}
                        <option value="{ow.id}">{ow.first_name} {ow.last_name}</option>
                        {/each}
                    </SelectInput>

                    <FileInput
                        className="pr-6 pb-8 w-full lg:w-1/4"
                        label="Imagen principal"
                        name="photo"
                        accept="image/*"
                        errors={errors.photo}
                        value={values.photo}
                        onChange={handleFileChange}
                    />

                    <div class="pr-6 pb-8 mb-3 w-full" style="height: 300px;">
                        <p class="pb-3">
                            Clic para ubicar la Propiedad
                        </p>
                        <Map {options} bind:this={MAP_EL} on:ready={init} />
                    </div>
                </div>

                <div class="px-8 py-4 bg-gray-100 border-t border-gray-200 flex items-center">
                    {#if !propertie.deleted_at}
                        <DeleteButton onDelete={destroy}>Desactivar Propiedad</DeleteButton>
                    {/if}

                    <LoadingButton
                        loading={sending}
                        type="submit"
                        className="ml-2 btn-indigo"
                    >
                        Editar Inmobiliaria
                    </LoadingButton>
                </div>
            </form>
        </div>
    </div>
</Layout>
