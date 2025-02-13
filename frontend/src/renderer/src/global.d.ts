export {};

declare global {
  interface Window {
    api: {
      generateHash: (data: string) => Promise<string>;
      isOnline: () => Promise<boolean>;
    };
  }
}
