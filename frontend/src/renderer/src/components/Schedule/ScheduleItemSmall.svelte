<script lang="ts">
  import type { ScheduleItemModel } from "../../models/scheduleItem.model";
  import { timeConverter } from "../../services/time.service";

  export let item: ScheduleItemModel;
</script>

<div
  class="flex p-2 rounded-lg flex-row items-center gap-2 text-sm"
  class:bg-red-300={item.changes.cancelled}
  class:bg-yellow-200={!item.changes.cancelled && item.type != "lesson"}
  class:bg-secondary-700={!item.changes.cancelled && item.type == "lesson"}
>
  <!-- Time/Hour -->
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
    <!-- Subject -->
    <p class="font-bold truncate">
      {item.subjectsFriendlyNames.length
        ? item.subjectsFriendlyNames.join(", ")
        : item.subjects}
    </p>

    <!-- Location & Teacher -->
    <p class="text-xs truncate">
      {item.locations.length ? item.locations.join(", ") : "?"}{item.changes
        .locationChanged
        ? "!"
        : ""}
      -
      {item.teachers.length ? item.teachers.join(", ") : "?"}{item.changes
        .teacherChanged
        ? "!"
        : ""}
    </p>
  </div>
</div>
