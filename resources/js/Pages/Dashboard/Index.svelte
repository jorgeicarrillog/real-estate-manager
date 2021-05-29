<script>
    import {InertiaLink} from '@inertiajs/inertia-svelte';
    import Layout from '@/Shared/Layout.svelte';
    import Helmet from '@/Shared/Helmet.svelte';
</script>

<Helmet title="Dashboard"/>

<Layout>
    <div>
        <h1 class="mb-8 font-bold text-3xl">Dashboard</h1>

        <p class="mb-12 leading-normal">
            ¡Hola! Bienvenido a Inmo CRM, una aplicación de demostración diseñada para ayudar a ilustrar cómo
            <a
                class="text-indigo-600 underline hover:text-orange-500 mx-1"
                href="https://inertiajs.com"
            >
                Inertia.js
            </a>
            trabaja con 
            <a
                class="text-indigo-600 underline hover:text-orange-500 ml-1"
                href="https://svelte.dev/"
            >
                Svelte
            </a>
            .
        </p>

        <div>
            <InertiaLink class="btn-indigo mr-1" href="/500">
                500 error
            </InertiaLink>

            <InertiaLink class="btn-indigo" href="/404">
                404 error
            </InertiaLink>
        </div>
    </div>
</Layout>
