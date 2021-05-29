<script>
    import { Inertia } from '@inertiajs/inertia';
    import { InertiaLink, page } from '@inertiajs/inertia-svelte';
    import Helmet from '@/Shared/Helmet.svelte';
    import Layout from '@/Shared/Layout.svelte';
    import LoadingButton from '@/Shared/LoadingButton.svelte';
    import TextInput from '@/Shared/TextInput.svelte';
    import SelectInput from '@/Shared/SelectInput.svelte';
    import FileInput from '@/Shared/FileInput.svelte';
    import { toFormData } from '@/utils';

    const route = window.route;

    $: errors = $page.errors;
    $: organizations = $page.organizations;

    let sending = false;
    let values = {
        first_name: '',
        last_name: '',
        email: '',
        photo: '',
        organization_id: ''
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
        // for more info check utils.js
        const formData = toFormData(values);

        Inertia.post(route('owners.store'), formData).then(() =>  sending = false);
    }
</script>

<Helmet title="Crear Propietario" />

<Layout>
    <div>
        <div>
            <h1 class="mb-8 font-bold text-3xl">
                <InertiaLink
                    href={route('owners')}
                    class="text-indigo-600 hover:text-indigo-700"
                >
                    Propietarios
                </InertiaLink>

                <span class="text-indigo-600 font-medium"> /</span> Crear
            </h1>
        </div>

        <div class="bg-white rounded shadow overflow-hidden max-w-3xl">
            <form name="createForm" on:submit|preventDefault={handleSubmit}>
                <div class="p-8 -mr-6 -mb-8 flex flex-wrap">
                    <TextInput
                        className="pr-6 pb-8 w-full lg:w-1/2"
                        label="Nombres"
                        name="first_name"
                        errors={errors.first_name}
                        value={values.first_name}
                        onChange={handleChange}
                        required
                    />

                    <TextInput
                        className="pr-6 pb-8 w-full lg:w-1/2"
                        label="Apellidos"
                        name="last_name"
                        errors={errors.last_name}
                        value={values.last_name}
                        onChange={handleChange}
                        required
                    />

                    <TextInput
                        className="pr-6 pb-8 w-full lg:w-1/2"
                        label="Correo"
                        name="email"
                        type="email"
                        errors={errors.email}
                        value={values.email}
                        onChange={handleChange}
                        required
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

                    <SelectInput
                        className="pr-6 pb-8 w-full"
                        label="Inmobiliaria"
                        name="organization_id"
                        errors={errors.organization_id}
                        value={values.organization_id}
                        onChange={handleChange}
                        required
                    >
                        <option value="">Seleccionar</option>
                        {#each organizations as org}
                        <option value="{org.id}">{org.name}</option>
                        {/each}
                    </SelectInput>
                </div>

                <div class="px-8 py-4 bg-gray-100 border-t border-gray-200 flex justify-end items-center">
                    <LoadingButton
                        loading={sending}
                        type="submit"
                        className="btn-indigo"
                    >
                        Crear Propietario
                    </LoadingButton>
                </div>
            </form>
        </div>
    </div>
</Layout>
