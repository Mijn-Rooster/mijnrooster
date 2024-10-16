<script lang="ts">
  import MenuBar from "../components/MenuBar.svelte";
  import { navigate } from "../stores/RouterStore";
  import { onMount } from "svelte";
  import { getCurrentTime } from "../services/TimeService";
  import { ButtonGroup, Button } from 'flowbite-svelte';
  import { CalendarMonthOutline, CloseCircleOutline, CogOutline} from 'flowbite-svelte-icons';

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
