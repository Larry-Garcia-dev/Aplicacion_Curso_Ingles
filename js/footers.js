// Footer JavaScript functionality
class FooterManager {
  constructor() {
    this.init()
  }

  init() {
    this.updateLastModified()
    this.setupSocialLinks()
    this.setupAnimations()
    this.setupAccessibility()
    this.setupPrintStyles()
  }

  // Update last modified date to current date
  updateLastModified() {
    const lastModifiedElement = document.getElementById("lastModified")
    if (lastModifiedElement) {
      const now = new Date()
      const options = {
        year: "numeric",
        month: "long",
        day: "numeric",
        hour: "2-digit",
        minute: "2-digit",
        hour12: true,
      }
      const formattedDate = now.toLocaleDateString("es-ES", options)
      lastModifiedElement.textContent = formattedDate
    }
  }

  // Setup social media links with analytics tracking
  setupSocialLinks() {
    const socialLinks = document.querySelectorAll(".social-link")

    socialLinks.forEach((link) => {
      link.addEventListener("click", (e) => {
        const platform = link.dataset.platform
        console.log(`[Footer] Social link clicked: ${platform}`)

        // Add analytics tracking here if needed
        this.trackSocialClick(platform)

        // Add visual feedback
        this.addClickFeedback(link)
      })
    })
  }

  // Track social media clicks (placeholder for analytics)
  trackSocialClick(platform) {
    // This would integrate with your analytics service
    console.log(`[Footer] Tracking social click: ${platform}`)

    // Example: Google Analytics event
    window.gtag =
      window.gtag ||
      (() => {}) // Declare gtag variable // Declare gtag variable
    if (typeof window.gtag !== "undefined") {
      window.gtag("event", "social_click", {
        social_platform: platform,
        page_location: window.location.href,
      })
    }
  }

  // Add visual feedback for clicks
  addClickFeedback(element) {
    element.style.transform = "scale(0.95)"
    element.style.transition = "transform 0.1s ease"

    setTimeout(() => {
      element.style.transform = ""
    }, 100)
  }

  // Setup scroll animations for footer sections
  setupAnimations() {
    const observerOptions = {
      threshold: 0.1,
      rootMargin: "0px 0px -50px 0px",
    }

    const observer = new IntersectionObserver((entries) => {
      entries.forEach((entry) => {
        if (entry.isIntersecting) {
          entry.target.style.animationDelay = `${Math.random() * 0.3}s`
          entry.target.classList.add("animate-in")
        }
      })
    }, observerOptions)

    // Observe all footer sections
    const sections = document.querySelectorAll(".footer-section")
    sections.forEach((section) => {
      observer.observe(section)
    })
  }

  // Setup accessibility features
  setupAccessibility() {
    // Add ARIA labels to social links
    const socialLinks = document.querySelectorAll(".social-link")
    socialLinks.forEach((link) => {
      const platform = link.dataset.platform || "red social"
      link.setAttribute("aria-label", `Visitar página de ${platform} de la Alcaldía`)
    })

    // Add ARIA labels to email links
    const emailLinks = document.querySelectorAll(".email-info a")
    emailLinks.forEach((link) => {
      link.setAttribute("aria-label", `Enviar correo a ${link.textContent}`)
    })

    // Add keyboard navigation support
    this.setupKeyboardNavigation()
  }

  // Setup keyboard navigation
  setupKeyboardNavigation() {
    const focusableElements = document.querySelectorAll(
      '.footer a, .footer button, .footer [tabindex]:not([tabindex="-1"])',
    )

    focusableElements.forEach((element) => {
      element.addEventListener("keydown", (e) => {
        if (e.key === "Enter" || e.key === " ") {
          if (element.tagName === "A") {
            // Let default behavior handle links
            return
          }
          e.preventDefault()
          element.click()
        }
      })
    })
  }

  // Setup print-friendly styles
  setupPrintStyles() {
    // Add print button functionality if needed
    window.addEventListener("beforeprint", () => {
      console.log("[Footer] Preparing for print...")
      // Add any print-specific modifications here
    })

    window.addEventListener("afterprint", () => {
      console.log("[Footer] Print completed")
      // Restore any modifications after printing
    })
  }

  // Utility method to copy contact information
  copyContactInfo(type) {
    let textToCopy = ""

    switch (type) {
      case "address":
        textToCopy = "Carrera 3 # 3 - 23 Barrio Centro, Casabianca, Tolima"
        break
      case "phone":
        textToCopy = "(038) 2548507"
        break
      case "mobile":
        textToCopy = "3175131279"
        break
      case "email":
        textToCopy = "alcaldia@casabianca-tolima.gov.co"
        break
      default:
        return
    }

    if (navigator.clipboard) {
      navigator.clipboard
        .writeText(textToCopy)
        .then(() => {
          this.showCopyFeedback(`${type} copiado al portapapeles`)
        })
        .catch((err) => {
          console.error("[Footer] Error copying to clipboard:", err)
        })
    }
  }

  // Show copy feedback
  showCopyFeedback(message) {
    // Create temporary feedback element
    const feedback = document.createElement("div")
    feedback.textContent = message
    feedback.style.cssText = `
            position: fixed;
            bottom: 20px;
            right: 20px;
            background: var(--primary);
            color: white;
            padding: 0.75rem 1rem;
            border-radius: 0.5rem;
            font-size: 0.875rem;
            z-index: 1000;
            animation: slideInUp 0.3s ease-out;
        `

    document.body.appendChild(feedback)

    // Remove after 3 seconds
    setTimeout(() => {
      feedback.style.animation = "slideOutDown 0.3s ease-in forwards"
      setTimeout(() => {
        document.body.removeChild(feedback)
      }, 300)
    }, 3000)
  }

  // Method to update footer content dynamically
  updateFooterContent(newData) {
    if (newData.lastModified) {
      const lastModifiedElement = document.getElementById("lastModified")
      if (lastModifiedElement) {
        lastModifiedElement.textContent = newData.lastModified
      }
    }

    // Add more dynamic content updates as needed
    console.log("[Footer] Content updated:", newData)
  }
}

// Initialize footer when DOM is loaded
document.addEventListener("DOMContentLoaded", () => {
  const footerManager = new FooterManager()

  // Make footer manager globally available if needed
  window.footerManager = footerManager

  console.log("[Footer] Footer initialized successfully")
})

// Add CSS animations dynamically
const style = document.createElement("style")
style.textContent = `
    @keyframes slideInUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @keyframes slideOutDown {
        from {
            opacity: 1;
            transform: translateY(0);
        }
        to {
            opacity: 0;
            transform: translateY(20px);
        }
    }

    .animate-in {
        animation: fadeInUp 0.6s ease-out forwards;
    }
`
document.head.appendChild(style)
