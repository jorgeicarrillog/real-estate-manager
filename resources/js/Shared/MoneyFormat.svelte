<script>
    export let maximumFractionDigits = 0;
    export let currency ='COP';
    export let value;

    var moneyFormatted = value.toLocaleString("es", {
    maximumFractionDigits: maximumFractionDigits,
    style: "currency",
    currency: currency,
    });
</script>
{moneyFormatted}