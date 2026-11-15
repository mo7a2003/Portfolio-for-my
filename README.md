# 🌌 Mohammed Alhwaiti — Futuristic Dark Neon 3D Portfolio

An ultra-luxury, hyper-realistic, futuristic dark neon portfolio website engineered for **Mohammed Alhwaiti** — Web Designer & Front-End Developer.

---

## 🎨 Design System & Palette

- **Obsidian Black (`#07090e`, `#0a0d14`)**: Deep cybernetic background base with ambient spatial depth.
- **Cyber Blue (`#00f0ff`, `#0070f3`)**: Electric neon accents, data telemetry glows, and holographic scanlines.
- **Luxury Gold (`#d4af37`, `#f59e0b`, `#fbbf24`)**: Metallic PBR specular highlights, glowing typographic gradients, and 3D mechanical gear plating.
- **Glassmorphism**: Multi-layer frosted panels with dynamic backdrop blur (`16px`), micro-borders, and interactive hover lighting.

---

## 💎 Features & Architecture

### 1. ⚙️ Interactive 3D Hero Scene (`Three.js`)
- **Sci-Fi Golden Mechanical Gear**: Procedural 16-tooth beveled golden gear rotating smoothly in real-time with metallic PBR materials (`metalness: 0.92, roughness: 0.22`).
- **Holographic Crystal Frame**: Faceted octagonal refractive crystal housing the portrait of Mohammed Alhwaiti with an electric cyber scanline rim.
- **Geometric Constellation Lattice**: 3D Icosahedron wireframe with dual-tone golden and cyan vertex nodes.
- **Orbiting Satellite Spheres**: Metallic chrome, gold, and cyan emissive spheres tracing multi-plane elliptical orbits.
- **Mouse Parallax & 360° Drag**: Interactive rotation with smooth inertia lerping on cursor drag and touch swipe.
- **3D Perspective Cyber Grid**: Deep vanishing point grid floor fading into the obsidian void.

### 2. ⚡ Skills & Technologies
- Futuristic glassmorphic badges with 3D hover lift and radial glows:
  - **Vue.js**, **Tailwind CSS**, **JavaScript (ES6+)**, **HTML5**, **CSS3**, **Node.js**, **Express.js**, **Laravel**, **PostgreSQL**, **Figma**, **Git**, and **Three.js / WebGL**.
- Category filter tabs (**All**, **Frontend**, **Backend**, **Styling**, **UI/UX**, **3D & Motion**).
- Interactive hover tooltips highlighting specialized proficiencies.

### 3. 🚀 Selected Projects
- Glassmorphic carousel and grid view for flagship projects:
  - **WASLA**: Multi-service platform with 3D dashboard & real-time telemetry.
  - **WAFARCASH**: 🏆 *1st Place Award-Winning* smart cash flow platform with 3D volumetric analytics.
  - **VIBELOCATE AI**: Spatial real estate discovery engine with 3D holographic district mapping.
  - **LUMIERE**: Haute cosmétique e-commerce platform with real-time 3D product rotators & PBR caustics.
- **Case Study Modal**: In-depth architecture breakdown, verified performance metrics, technical highlights, and direct live links.

### 4. 📬 Glassmorphic "Let's Connect" Section
- Sleek contact form with instant validation, interactive submit state, and direct contact telemetry.

### 5. 🔊 Synthesized Web Audio FX
- Built-in Web Audio API synthesizer for subtle futuristic UI feedback on interactions (with a one-click mute/unmute toggle in the navbar).

---

## 🛠️ Project Structure

```
Portfolio/
├── public/
│   ├── favicon.svg                # Sci-Fi Golden 'M' emblem
│   ├── mohammed-avatar.jpg        # High-resolution avatar portrait
│   └── projects/                  # High-tech isometric SVG mockups
│       ├── wasla-preview.svg
│       ├── wafarcash-preview.svg
│       ├── vibelocate-preview.svg
│       └── lumiere-preview.svg
├── src/
│   ├── assets/                    # Static image assets
│   ├── components/
│   │   ├── Navbar.vue             # Header navigation with avatar pill & sound toggle
│   │   ├── HeroSection.vue        # Hero typography, buttons & impact stats
│   │   ├── HeroCanvas3D.vue       # Three.js 3D Golden Gear & Crystal Portrait scene
│   │   ├── SkillsSection.vue      # Hexagonal/shield glassmorphic skill cards
│   │   ├── ProjectsSection.vue    # Interactive carousel & project cards
│   │   ├── ProjectModal.vue       # In-depth case study modal dialog
│   │   ├── ContactSection.vue     # Glassmorphic contact form
│   │   └── Footer.vue             # Footer navigation, social links & back to top
│   ├── data/
│   │   └── portfolioData.js       # Complete structured bio, stats, skills & projects
│   ├── App.vue                    # Master Vue component with ambient mouse light
│   ├── main.js                    # Vite Vue initialization
│   └── style.css                  # Tailwind directives & glassmorphic custom styles
├── dist/                          # Production-ready build
├── package.json
├── tailwind.config.js
└── vite.config.js
```

---

## 🚀 Running the Project

### Development Server
```bash
npm run dev
```
Open [http://localhost:5173](http://localhost:5173) in your browser.

### Production Build
```bash
npm run build
```
Generates optimized static files in `/dist`.

### Production Preview
```bash
npm run preview
```

