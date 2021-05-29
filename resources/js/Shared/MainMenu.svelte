<script>
    export let className = '';

    import MainMenuItem from '@/Shared/MainMenuItem.svelte';
</script>

<div class={className}>
    <MainMenuItem text="Dashboard" link="dashboard" icon="dashboard"/>
    <MainMenuItem text="Inmobiliarias" link="organizations" icon="office"/>
    <MainMenuItem text="Usuarios" link="users" icon="users"/>
    <MainMenuItem text="Reports" link="reports" icon="printer"/>
</div>
