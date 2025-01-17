<script lang="ts">
  import MenuBar from "../components/MenuBar.svelte";
  import { navigate } from "../stores/RouterStore";
  import { onMount } from "svelte";
  import { getCurrentTime, getCurrentDate } from "../services/TimeService";
  import { ButtonGroup, Button } from "flowbite-svelte";
  import {
    CalendarMonthOutline,
    CloseCircleOutline,
    CogOutline,
    ProfileCardSolid,
    ArrowLeftToBracketOutline,
  } from "flowbite-svelte-icons";
  import { Footer } from "flowbite-svelte";

  $: currentDate = getCurrentDate();

  let currentTime: string;

  onMount(() => {
    currentTime = getCurrentTime();
    const interval = setInterval(() => {
      currentTime = getCurrentTime();
    }, 1000);
    return () => clearInterval(interval);
  });
</script>

<MenuBar timeVisible={false} />

<h1 class="text-4xl font-extrabold text-center w-full">{currentTime}</h1>
<h2 class="text-xl font-bold text-center w-full">{currentDate}</h2>

<div class="flex flex-col items-center mt-8">
  <ButtonGroup class="*:!ring-primary-700">
    <Button on:click={() => navigate("/schedule")}>
      <CalendarMonthOutline class="w-4 h-4 me-2" />
      Schedule
    </Button>
    <Button on:click={() => navigate("/error")}>
      <CloseCircleOutline class="w-4 h-4 me-2" />
      Error
    </Button>
    <Button on:click={() => navigate("/setup")}>
      <CogOutline class="w-4 h-4 me-2" />
      Setup
    </Button>
  </ButtonGroup>
</div>

<div class="flex-grow flex flex-col items-center justify-center">
  <ProfileCardSolid class="size-40" style="color: #291c5b;" />
  <p class="text-2xl text-center">
    Scan je schoolpas om je rooster te bekijken <br /><br />
    of <br /><br />
  </p>
  <Button on:click={() => navigate("/inloggen")} class="bg-primary-700 text-white">
    <ArrowLeftToBracketOutline class="w-4 h-4 me-2" />
    Log in met je leerlingnummer
  </Button>

</div>
