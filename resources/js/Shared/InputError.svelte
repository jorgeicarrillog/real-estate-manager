<script>
    export let message
    export let classes
</script>

{#if message}
    <div class="text-red-400 mt-2 bg-red-100 p-4 {classes}">
        <p>
            {message}
        </p>
    </div>
{/if}
