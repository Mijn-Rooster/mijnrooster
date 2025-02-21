export {};

declare global {
  interface Window {
    api: {
      generateHash: (data: string) => Promise<string>;
      isOnline: () => Promise<boolean>;
      onOpenSettings: (callback: () => void) => void;
      appInfo: () => {
        appVersion: string;
        electronVersion: string;
        nodeVersion: string;
        chromeVersion: string;
        v8Version: string;
      };
      getAutoLaunchStatus: () => Promise<boolean>;
      setAutoLaunch: (enabled: boolean) => Promise<boolean>;
    };
  }
}
