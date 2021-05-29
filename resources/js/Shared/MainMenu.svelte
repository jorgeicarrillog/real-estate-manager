<script>
    import { page } from '@inertiajs/inertia-svelte';
    import MainMenuItem from '@/Shared/MainMenuItem.svelte';

    export let className = '';

    let { auth } = $page;
    $: auth = $page.auth;
</script>

<div class={className}>
    <MainMenuItem text="Dashboard" link="dashboard" icon="dashboard"/>
    {#if auth.user.organizations_count>0}
    <MainMenuItem text="Inmobiliarias" link="organizations" icon="office"/>
    <MainMenuItem text="Propietarios" link="owners" icon="owners"/>
    {/if}
    {#if auth.user.role=='owner'}
    <MainMenuItem text="Usuarios" link="users" icon="users"/>
    <MainMenuItem text="Reports" link="reports" icon="printer"/>
    {/if}
</div>
