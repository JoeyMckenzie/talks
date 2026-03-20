---
title: Terminal Tools That Big GUI Doesn't Want You to Know About
author: Joey McKenzie
theme:
  override:
    default:
      colors:
        foreground: "bfbdb6"
        background: "0d1117"
    headings:
      colors:
        foreground: "e06c75"
---

# Terminal Tools That Big GUI Doesn't Want You to Know About

![image:width:50%](../docs/images/terminal-hero.png)

<!-- speaker_note: 30s. Ask for show of hands. Energy up. -->

<!-- pause -->

**The terminal isn't outdated. The default tools are.**

<!-- pause -->

Quick show of hands...

- Who uses the terminal daily?
- Who uses a GUI for git?

<!-- end_slide -->

# Let's start with the simplest thing you do every day — read a file

<!-- end_slide -->

<!-- new_lines: 3 -->

# bat

## A better `cat` with syntax highlighting, line numbers, and git integration

<!-- pause -->

```bash +exec
cat docs/demos/legacy-config.php
```

<!-- pause -->

```bash +exec
bat docs/demos/legacy-config.php
```

<!-- end_slide -->

# OK so we can read files better. But can we even see what's in this directory?

<!-- end_slide -->

<!-- new_lines: 3 -->

# eza

## A modern `ls` replacement with icons, colors, git status, and tree view

<!-- speaker_note: 1 min. Let the side-by-side speak for itself. Mention you aliased ls to eza. -->

<!-- pause -->

```bash +exec
ls -la docs/demos
```

<!-- pause -->

```bash +exec
eza -la --icons --git docs/demos
```

<!-- end_slide -->

## But wait, there's a tree

```bash +exec
eza --tree --level=2 --icons --git docs/
```

<!-- pause -->

_I aliased `ls` to `eza` six months ago. Haven't looked back._

<!-- end_slide -->

# We can read and list files. But what about navigating the whole project?

<!-- end_slide -->

<!-- new_lines: 3 -->

# yazi

## A terminal file manager with preview, bulk ops, and a plugin system

<!-- speaker_note: 1.5 min. Switch to demo pane, launch yazi, navigate around. Show preview pane and bulk rename. -->

<!-- pause -->

**Demo time:** _Launch `yazi` in the project root._

- Arrow keys to navigate, Enter to open
- Preview pane: syntax highlighted code, images, even PDFs
- Bulk rename, copy, move — Finder but faster
- Tab for new tabs, `~` for hidden files

<!-- pause -->

_The moment you stop `cd`-ing and `ls`-ing to find that one file..._

<!-- end_slide -->

# We can read, list, and navigate. But sometimes we need to _search_

<!-- end_slide -->

<!-- new_lines: 3 -->

# fd + ripgrep + fzf

## Find files. Search content. Filter anything

<!-- end_slide -->

## "I need to find all env files"

```bash +exec
fd -H .env docs/demos
```

<!-- pause -->

## "Which one has my Stripe key?"

```bash +exec
rg --hidden STRIPE_KEY docs/demos
```

<!-- end_slide -->

## "What if I don't know exactly what I'm looking for?"

```bash +exec
rg "TODO" docs/demos
```

_Pipe it to fzf for interactive filtering: `rg "TODO" | fzf`_

<!-- pause -->

## One more thing

**Hit CTRL+R** — fzf already replaced your shell history search.

<!-- end_slide -->

# We can find and read code. What about what's _changed_?

<!-- end_slide -->

<!-- new_lines: 3 -->

# delta

## A better git diff — syntax highlighted, readable, side-by-side

<!-- pause -->

```bash +exec
git diff --no-index docs/demos/old-api.php docs/demos/new-api.php | delta --side-by-side
```

<!-- end_slide -->

# Delta fixed the diff. Lazygit fixes the whole workflow

<!-- end_slide -->

<!-- new_lines: 3 -->

# lazygit

## A full git workflow from a single TUI — no git commands required

<!-- speaker_note: 2 min. Longest demo. Stage a file, show diff, interactive rebase. -->

<!-- pause -->

**Demo time:** staging, diffs, interactive rebase — without typing a single git command.

_Launch: `lazygit`_

<!-- end_slide -->

# That's git. But what about everything else you typed today?

<!-- end_slide -->

<!-- new_lines: 3 -->

# atuin

## Shell history that's actually searchable — with timestamps, exit codes, and cross-machine sync

<!-- pause -->

**Demo:** Hit **CTRL+R** — searchable history UI.

- Timestamps on every command
- Exit codes — see that red X? That command failed last Tuesday.
- Syncs across machines.

<!-- end_slide -->

# Shoot, I mistyped that command

<!-- end_slide -->

<!-- new_lines: 3 -->

# thefuck

## Auto-corrects your previous command when you mess up

<!-- pause -->

_Demo: typo a command, then just type `fuck`._

<!-- end_slide -->

# I have my history, my git, my search — but I'm drowning in terminal tabs

<!-- end_slide -->

<!-- new_lines: 3 -->

# zellij

## A terminal multiplexer that doesn't require a PhD to configure

<!-- pause -->

## You've been looking at zellij this whole time

<!-- pause -->

This presentation layout? The split panes? All zellij.

```bash +exec
cat docs/demos/talk-layout.kdl
```

<!-- end_slide -->

# Now that we have our workspace, let's talk about replacing entire applications

<!-- end_slide -->

<!-- new_lines: 3 -->

# btop

## System monitoring that makes Activity Monitor look like a spreadsheet

<!-- pause -->

_The progression:_

`top` → `htop` → **`btop`**

<!-- pause -->

**Demo:** _Launch `btop` — CPU, memory, network, disks, process tree. All in one view._

<!-- end_slide -->

# If btop can replace Activity Monitor, what else can we replace?

<!-- end_slide -->

<!-- new_lines: 3 -->

# harlequin

## A TUI database client — query your DB without leaving the terminal

<!-- pause -->

**Demo:** Connect to a local DB, run a query, scroll through results, autocomplete on table/column names.

_Launch: `harlequin`_

<!-- pause -->

The reaction moment: "wait, this is actually usable"

<!-- end_slide -->

# If I can query my database from the terminal, why am I opening Postman?

<!-- end_slide -->

<!-- new_lines: 3 -->

# hurl

## HTTP requests as plain text files — committable, chainable, scriptable

<!-- pause -->

```bash +exec
cat docs/demos/api-flow.hurl
```

<!-- pause -->

```bash +exec
hurl --very-verbose docs/demos/api-flow.hurl
```

<!-- pause -->

**Key sell:** "This is just a file. I can commit it, diff it, run it in CI."

<!-- end_slide -->

# We replaced Postman. Let's replace the browser for one more thing — reading docs

<!-- end_slide -->

<!-- new_lines: 3 -->

# glow

## Render Markdown beautifully in the terminal

<!-- pause -->

```bash +exec
glow docs/demos/sample-readme.md
```

<!-- pause -->

_Pager mode: `glow -p README.md`_

<!-- end_slide -->

# Everything so far is a better version of something that already exists. This last one is something new

<!-- end_slide -->

<!-- new_lines: 3 -->

# taskwarrior + MCP

## Full task management from the terminal — and from Claude Code via a custom MCP

<!-- speaker_note: 2.5 min. This is the closer. Switch to Claude Code pane. -->

<!-- end_slide -->

## The Problem

I keep track of tasks across three systems.

I wanted one place. In my terminal.

<!-- pause -->

## taskwarrior — the quick version

```bash +exec
task list
```

<!-- end_slide -->

## The Demo

Switch to Claude Code (already in this zellij layout — callback!)

<!-- pause -->

1. "Add a high-priority task to review the API docs before Friday's release"
2. Claude calls the MCP → task appears
3. "What's on my plate this week?" → Claude queries and summarizes
4. "Mark the docs review as done" → Claude completes it

<!-- pause -->

**~200 lines of code. One afternoon. Claude manages my tasks without me remembering a single flag.**

<!-- end_slide -->

<!-- new_lines: 3 -->

# The terminal isn't dying. It's having a renaissance

![image:width:60%](../docs/images/terminal-renaissance.png)

<!-- speaker_note: Point to reference table. Link to repo. -->

<!-- end_slide -->

# Tools Reference

| Tool        | Install                   |
| ----------- | ------------------------- |
| bat         | `brew install bat`        |
| eza         | `brew install eza`        |
| yazi        | `brew install yazi`       |
| fd          | `brew install fd`         |
| ripgrep     | `brew install ripgrep`    |
| fzf         | `brew install fzf`        |
| delta       | `brew install git-delta`  |
| lazygit     | `brew install lazygit`    |
| atuin       | `brew install atuin`      |
| zellij      | `brew install zellij`     |
| thefuck     | `brew install thefuck`    |
| btop        | `brew install btop`       |
| harlequin   | `pipx install harlequin`  |
| hurl        | `brew install hurl`       |
| glow        | `brew install glow`       |
| taskwarrior | `brew install task`       |
| presenterm  | `brew install presenterm` |

<!-- end_slide -->

# Thanks

Slides and demos: **github.com/joeymckenzie/talks**
