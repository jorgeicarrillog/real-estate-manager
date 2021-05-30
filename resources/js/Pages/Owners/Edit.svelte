<script>
    import { Inertia } from '@inertiajs/inertia';
    import { InertiaLink, page } from '@inertiajs/inertia-svelte';
    import Helmet from '@/Shared/Helmet.svelte';
    import Layout from '@/Shared/Layout.svelte';
    import DeleteButton from '@/Shared/DeleteButton.svelte';
    import LoadingButton from '@/Shared/LoadingButton.svelte';
    import TextInput from '@/Shared/TextInput.svelte';
    import SelectInput from '@/Shared/SelectInput.svelte';
    import FileInput from '@/Shared/FileInput.svelte';
    import Icon from '@/Shared/Icon.svelte';
    import Pagination from '@/Shared/Pagination.svelte';
    import { toFormData } from '@/utils';
    import MoneyFormat from '@/Shared/MoneyFormat.svelte';

    const route = window.route;

    let { owner } = $page;
    $: owner = $page.owner;
    $: errors = $page.errors;
    $: organizations = $page.organizations;
    $: properties = $page.properties.data;
    $: links = $page.properties.links;

    let sending = false;
    let values = {
        organization_id: owner.organization_id || '',
        first_name: owner.first_name || '',
        last_name: owner.last_name || '',
        email: owner.email || '',
        password: owner.password || '',
        owner: owner.owner ? '1' : '0' || '0',
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

        Inertia.post(route('owners.update', owner.id), formData).then(() => sending = false);
    }

    function destroy() {
        if (confirm('Are you sure you want to delete this owner?')) {
            Inertia.delete(route('owners.destroy', owner.id));
        }
    }

    function restore() {
        if (confirm('Are you sure you want to restore this owner?')) {
            Inertia.put(route('owners.restore', owner.id));
        }
    }
</script>

<Helmet title={`${values.first_name} ${values.last_name}`} />

<Layout>
    <div>
        <div class="mb-8 flex justify-start max-w-lg">
            <h1 class="font-bold text-3xl">
                <InertiaLink
                    href={route('owners')}
                    class="text-indigo-600 hover:text-indigo-700"
                >
                Propietario
                </InertiaLink>

                <span class="text-indigo-600 font-medium mx-2">/</span>
                {values.first_name} {values.last_name}
            </h1>

            {#if owner.photo}
                <img class="block w-8 h-8 rounded-full ml-4" src={owner.photo} alt={owner.name} />
            {/if}
        </div>

        <div class="bg-white rounded shadow overflow-hidden max-w-3xl">
            <form on:submit|preventDefault={handleSubmit}>
                <div class="p-8 -mr-6 -mb-8 flex flex-wrap">
                    <TextInput
                        className="pr-6 pb-8 w-full lg:w-1/2"
                        label="First Name"
                        name="first_name"
                        errors={errors.first_name}
                        value={values.first_name}
                        onChange={handleChange}
                    />

                    <TextInput
                        className="pr-6 pb-8 w-full lg:w-1/2"
                        label="Last Name"
                        name="last_name"
                        errors={errors.last_name}
                        value={values.last_name}
                        onChange={handleChange}
                    />

                    <TextInput
                        className="pr-6 pb-8 w-full lg:w-1/2"
                        label="Email"
                        name="email"
                        type="email"
                        errors={errors.email}
                        value={values.email}
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

                    <SelectInput
                        className="pr-6 pb-8 w-full "
                        label="Owner"
                        name="organization_id"
                        errors={errors.organization_id}
                        value={values.organization_id}
                        onChange={handleChange}
                    >
                        <option value="">Seleccionar</option>
                        {#each organizations as org}
                        <option value="{org.id}">{org.name}</option>
                        {/each}
                    </SelectInput>
                </div>

                <div class="px-8 py-4 bg-gray-100 border-t border-gray-200 flex items-center">
                    <DeleteButton onDelete={destroy}>Eliminar Propietario</DeleteButton>

                    <LoadingButton
                        loading={sending}
                        type="submit"
                        className="btn-indigo ml-auto"
                    >
                        Actualizar Propietario
                    </LoadingButton>
                </div>
            </form>
        </div>
        
        <div>
            <h3 class="my-8 font-bold text-3xl">Propiedades</h3>
            <div class="mb-6 flex justify-between items-center">

                <InertiaLink
                    class="btn-indigo"
                    href={route('organizations.create')}
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
                                            href={route('organizations.edit', pty.id)}
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
                                            href={route('organizations.edit', pty.id)}
                                            class="px-6 py-4 flex items-center focus:text-indigo"
                                        >
                                            {pty.citie.name}
                                        </InertiaLink>
                                    </td>

                                    <td class="border-t">
                                        <InertiaLink
                                            tabindex="-1"
                                            href={route('organizations.edit', pty.id)}
                                            class="px-6 py-4 flex items-center focus:text-indigo"
                                        >
                                            {pty.properties_type.name}
                                        </InertiaLink>
                                    </td>

                                    <td class="border-t">
                                        <InertiaLink
                                            tabindex="-1"
                                            href={route('organizations.edit', pty.id)}
                                            class="px-6 py-4 flex items-center focus:text-indigo"
                                        >
                                            <MoneyFormat value={pty.value}></MoneyFormat>
                                        </InertiaLink>
                                    </td>

                                    <td class="border-t w-px">
                                        <InertiaLink
                                            tabindex="-1"
                                            href={route('organizations.edit', pty.id)}
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
