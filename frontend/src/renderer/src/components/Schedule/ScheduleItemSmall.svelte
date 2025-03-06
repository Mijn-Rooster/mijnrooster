<script lang="ts">
  import type { ScheduleItemModel } from "../../models/scheduleItem.model";
  import { timeConverter } from "../../services/time.service";

  export let item: ScheduleItemModel;

  // Check if this is the current lesson
  let isCurrentLesson = checkCurrentLesson();

  // Function to check if this is the current lesson
  function checkCurrentLesson(): boolean {
    const currentTime = new Date();
    const startTime = new Date(item.start * 1000);
    const endTime = new Date(item.end * 1000);
    return currentTime >= startTime && currentTime <= endTime;
  }
</script>

<div
  class="flex p-2 rounded-lg flex-row items-center gap-2 text-sm {isCurrentLesson
    ? 'border-l-4 border-primary-500'
    : ''}"
  class:bg-red-300={item.changes.cancelled}
  class:bg-yellow-200={!item.changes.cancelled && item.type != "lesson"}
  class:bg-secondary-700={!item.changes.cancelled && item.type == "lesson"}
>
  <!-- Time/Hour indicator -->
  <span
    class="text-white px-2 py-1 rounded-xl bg-primary-700 text-xs flex flex-col items-center min-w-[2.5rem]"
  >
    {#if item.lessonNumberStart && item.lessonNumberStart === item.lessonNumberEnd}
      {item.lessonNumberStart}
    {:else if item.lessonNumberStart && item.lessonNumberEnd}
      {item.lessonNumberStart}
      <span class="text-[0.6rem] leading-none">-</span>
      {item.lessonNumberEnd}
    {:else}
      {timeConverter(item.start)}
      <span class="text-[0.6rem] leading-none">-</span>
      {timeConverter(item.end)}
    {/if}
  </span>

  <div class="flex flex-col min-w-0">
    <div class="flex items-center gap-1">
      <p class="font-bold truncate">
        {item.subjectsFriendlyNames.length
          ? item.subjectsFriendlyNames.join(", ")
          : item.subjects}
      </p>
    </div>

    <!-- Location & Teacher -->
    <p class="text-xs truncate">
      {#if item.locations.length == 0}
        <span class="text-red-800">Geen lokaal</span>
      {:else}
        {item.locations.join(", ")}
      {/if}
      {#if item.changes.locationChanged}
        <span class="text-red-900 font-bold">!</span>
      {/if}
      -
      {#if item.teachers.length == 0}
        <span class="text-red-800">Geen docent</span>
      {:else}
        {item.teachers.join(", ")}
      {/if}
      {#if item.changes.teacherChanged}
        <span class="text-red-900 font-bold">!</span>
      {/if}
    </p>
  </div>
</div>
