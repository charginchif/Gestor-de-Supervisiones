
import * as React from "react"
import { Slot } from "@radix-ui/react-slot"
import { cva, type VariantProps } from "class-variance-authority"

import { cn } from "@/lib/utils"

const buttonVariants = cva(
  "inline-flex items-center justify-center gap-2 whitespace-nowrap rounded-full text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 [&_svg]:pointer-events-none [&_svg]:size-4 [&_svg]:shrink-0",
  {
    variants: {
      variant: {
        default: "bg-primary text-primary-foreground hover:bg-primary/90",
        destructive:
          "bg-destructive text-destructive-foreground hover:bg-destructive/90",
        "destructive-outline":
          "border border-destructive bg-transparent text-destructive hover:bg-destructive/10",
        outline:
          "border border-input bg-transparent hover:bg-white/10 hover:text-accent-foreground",
        "outline-filter":
           "border border-input bg-transparent hover:bg-white/10 hover:text-muted-foreground",
        secondary:
          "bg-secondary/50 text-secondary-foreground hover:bg-secondary/80",
        ghost: "hover:bg-sidebar-accent hover:text-sidebar-accent-foreground",
        warning:
          "bg-yellow-500 text-white hover:bg-yellow-600",
        success:
          "bg-[hsl(var(--success))] text-white hover:bg-[hsl(var(--success))]/90",
        info:
          "border-transparent bg-blue-500/80 text-white",
        "info-outline":
          "border border-blue-500/80 bg-transparent text-blue-400 hover:bg-blue-500/20 hover:text-blue-300"
      },
      size: {
        default: "h-10 px-4 py-2",
        sm: "h-9 rounded-full px-3",
        lg: "h-11 rounded-full px-8",
        icon: "h-10 w-10",
      },
    },
    defaultVariants: {
      variant: "default",
      size: "default",
    },
  }
)

export interface ButtonProps
  extends React.ButtonHTMLAttributes<HTMLButtonElement>,
    VariantProps<typeof buttonVariants> {
  asChild?: boolean
}

const Button = React.forwardRef<HTMLButtonElement, ButtonProps>(
  ({ className, variant, size, asChild = false, ...props }, ref) => {
    const Comp = asChild ? Slot : "button"
    return (
      <Comp
        className={cn(buttonVariants({ variant, size, className }))}
        ref={ref}
        {...props}
      />
    )
  }
)
Button.displayName = "Button"

export { Button, buttonVariants }
