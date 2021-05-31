<script>
    import MainMenuItemWeb from '@/Shared/MainMenuItemWeb.svelte';

    export let className = '';
</script>
<nav class={className}>
    <MainMenuItemWeb text="Inicio" link="home" icon="home"/>
  </nav>