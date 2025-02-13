<script lang="ts">
  import Router from "./components/Router.svelte";
</script>

<style>
  /* Remove focus outline */
  :global(svg:focus) {
    outline: none !important;
  }

  /* Remove focus outline on buttons */
  :global(button:focus) {
    outline: none !important;
  }
</style>

<Router />
