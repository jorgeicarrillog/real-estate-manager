<script>
    import {InertiaLink} from '@inertiajs/inertia-svelte';
    import Icon from '@/Shared/Icon.svelte';

    export let icon;
    export let link;
    export let text;

    const route = window.route;
    let isActive = route().current(link + '*');
</script>

<InertiaLink href={route(link)} class="{isActive ? 'mr-5 hover:text-gray-700 flex' : 'mr-5 hover:text-gray-900 flex'}">
    {#if icon}
    <Icon name={icon} className="w-4 h-4 mr-2 {isActive ? 'text-white fill-current' : 'text-indigo-400 group-hover:text-white fill-current'}" />
    {/if}
    {text}
</InertiaLink>

