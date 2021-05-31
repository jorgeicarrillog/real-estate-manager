<script>
    import FlashMessages from '@/Shared/FlashMessages.svelte';
    import TopHeaderWeb from '@/Shared/TopHeaderWeb.svelte';
    import FooterWeb from './FooterWeb.svelte';
</script>

<div>
    <TopHeaderWeb />
    <FlashMessages />
</div>
<div>
    <slot />
</div>
<FooterWeb></FooterWeb>