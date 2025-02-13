<script lang="ts">
  import MenuBar from "../components/MenuBar.svelte";
  import { navigate } from "../stores/router.store";
  import { route } from "../stores/router.store";
  import type { ErrorModel } from "../models/error.model";

  const error : ErrorModel = $route.params;

  // Close the error page when the escape key is pressed
  window.addEventListener("keydown", (e) => {
    if (e.key === "Escape") {
      navigate("/");
    }
  });

</script>

<MenuBar timeVisible={true} />

<div class="h-[calc(100vh-128px)] flex items-start justify-center pt-32">
  <div class="mx-auto w-full max-w-[1000px] flex flex-col gap-4">
    <h1 class="text-5xl font-bold mb-2">{error.message || "Oeps!"}</h1>
    <p class="text-xl w-full">
      {error.details || "Er is iets fout gegaan"}
    </p>
  </div>
</div>
