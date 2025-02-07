<script lang="ts">
  import { Button, Label, Input, Footer } from "flowbite-svelte";
  import { navigate } from "../../stores/router.store";
  import { ArrowRightOutline } from "flowbite-svelte-icons";
</script>

<div class="mx-auto pt-10 w-full max-w-[800px] max-h-200px flex flex-col gap-10 overflow-y-auto">
    <div>
        <h1 class="text-5xl font-bold mb-2">Welkom bij Mijn Rooster!</h1>
        <p class="text-xl w-full">Om te beginnen hebben we wat informatie nodig. <br> We gaan eerst verbinding maken met de Mijn Rooster server.</p>
    </div>

    <form class="flex flex-col space-y-6" action="/">
    <Label class="space-y-2">
      <span>Server URL</span>
      <Input
        type="url"
        name="serverUrl"
        placeholder="https://localhost:8000"
        required
      />
    </Label>
    <Label class="space-y-2">
      <span>Koppelwachtwoord</span>
      <Input type="password" name="serverPassword" placeholder="Welkom01!" required />
    </Label>
  </form>
</div>

<Footer
  class="absolute bottom-0 start-0 z-20 w-full p-4 bg-white border-t border-gray-200 shadow flex items-center justify-end"
>
  <Button class="gap-2 px-2">Verder<ArrowRightOutline /></Button>
</Footer>
